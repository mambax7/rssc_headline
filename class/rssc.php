<?php
// $Id: rssc.php,v 1.1 2011/12/29 14:41:32 ohwada Exp $

// 2007-06-01 K.OHWADA
// link_xml_handler

// 2006-09-20 K.OHWADA
// use rssc_xml_utlity : not use rssc_link_exist_handler
// add refresh_for_add_headline()

//=========================================================
// RSSC HeadLine
// 2006-07-02 K.OHWADA
//=========================================================

// dir name
define('RSSC_HEADLINE_RSSC_DIRNAME', 'rssc');

// RSSC files
if (file_exists(XOOPS_ROOT_PATH . '/modules/' . RSSC_HEADLINE_RSSC_DIRNAME . '/api/rssc_headline.php')) {
    require_once XOOPS_ROOT_PATH . '/modules/' . RSSC_HEADLINE_RSSC_DIRNAME . '/api/rssc_headline.php';
} else {
    die('require RSSC module');
}

//=========================================================
// class ahl rssc handler
// 2005-10-20 K.OHWADA
//=========================================================
class rssc_headlineRsscHandler extends rssc_error
{
    // config constant
    public $FORMAT_DATE = 'l';    // l=long, r=rfc822

    // handler
    public $_rssc_refreshHandler;
    public $_rssc_viewHandler;
    public $_rssc_link_xmlHandler;
    public $_rssc_utility;

    // set variable
    public $_mid;

    // result
    public $_result;
    public $_lid_exist = 0;
    public $_xml_mode  = 0;
    public $_rdf_url;
    public $_rss_url;
    public $_atom_url;
    public $_parse_result;

    public $_rssc_link_obj;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_rssc_refreshHandler  = rssc_getHandler('refresh', RSSC_HEADLINE_RSSC_DIRNAME);
        $this->_rssc_viewHandler     = rssc_getHandler('view', RSSC_HEADLINE_RSSC_DIRNAME);
        $this->_rssc_link_xmlHandler = rssc_getHandler('link_xml', RSSC_HEADLINE_RSSC_DIRNAME);
        $this->_rssc_utility         = rssc_xml_utility::getInstance();

        global $xoopsModule;
        if (is_object($xoopsModule)) {
            $this->set_mid($xoopsModule->getVar('mid'));
        }
    }

    //=========================================================
    // for class rssc_headline_Renderer
    //=========================================================
    public function update_cache($rssc_lid)
    {
        $this->_rssc_refreshHandler->setPriorityRssAtom(RSSC_C_SEL_RSS);

        return $this->_rssc_refreshHandler->refresh($rssc_lid);
    }

    public function fetch_cache($rssc_lid)
    {
        $this->_rssc_viewHandler->set_max_title(-1);    // unlimited
        $this->_rssc_viewHandler->set_max_content(-1);
        $this->_result = &$this->_rssc_viewHandler->get_sanitized_store_by_lid($rssc_lid);
    }

    public function &get_channel()
    {
        $channel_obj = &$this->_rssc_viewHandler->create_channel();
        $channel_obj->set_vars($this->_get_result('channel'));
        $channel_obj->format_for_rss($this->FORMAT_DATE);
        $arr = &$channel_obj->get_vars();

        return $arr;
    }

    public function &get_image()
    {
        return $this->_get_result('image');
    }

    public function &get_items()
    {
        $ret   = false;
        $items = $this->_get_result('items');

        if ($items) {
            $items_obj = &$this->_rssc_viewHandler->create_items();
            $items_obj->set_vars($items);
            $items_obj->format_for_rss($this->FORMAT_DATE);
            $ret = &$items_obj->get_vars();
        }

        return $ret;
    }

    public function &_get_result($key)
    {
        $ret = false;
        if ($this->_result[$key]) {
            $ret = $this->_result[$key];
        }

        return $ret;
    }

    public function set_force_update($force_update = false)
    {
        $this->_rssc_refreshHandler->set_force_refresh($force_update);
    }

    public function set_max_num_feed($value)
    {
        $this->_rssc_viewHandler->setFeedLimit($value);
    }

    //=========================================================
    // for admin/index.php
    //=========================================================
    public function check_add_headline()
    {
        $this->_clear_errors();

        $sel      = RSSC_C_SEL_RSS;
        $url      = $this->get_post_url($_POST, 'headline_url');
        $rss_url  = $this->get_post_url($_POST, 'headline_rssurl');
        $rdf_url  = '';
        $atom_url = '';

        if ($rss_url && ('https://' != $rss_url)) {
            $mode = RSSC_C_MODE_RSS;
        } else {
            $mode = RSSC_C_MODE_AUTO;
        }

        // discover xml link when auto mode
        $ret = $this->_rssc_utility->discover_for_manage($mode, $url, $rdf_url, $rss_url, $atom_url, $sel);
        if (RSSC_CODE_DISCOVER_FAILED == $ret) {
            $this->_set_errors($this->_rssc_utility->getErrors());

            return $ret;
        }

        $this->_xml_mode = $this->_rssc_utility->get_xml_mode();
        $this->_rdf_url  = $this->_rssc_utility->get_rdf_url();
        $this->_rss_url  = $this->_rssc_utility->get_rss_url();
        $this->_atom_url = $this->_rssc_utility->get_atom_url();

        // check exist rss link
        $list = $this->_rssc_link_xmlHandler->get_list_by_rssurl($this->_rdf_url, $this->_rss_url, $this->_atom_url);
        if ($list) {
            $this->_set_errors(_RSSC_LINK_ALREADY);
            $script = XOOPS_URL . '/modules/' . RSSC_HEADLINE_RSSC_DIRNAME . '/admin/';
            $script .= 'link_manage.php?op=mod_form&amp;lid=';
            $mag    = $this->_rssc_link_xmlHandler->build_error_rssurl_list($list, $script);
            $this->_set_errors($mag);
            $this->_lid_exist = $list[0];

            return RSSC_CODE_LINK_ALREADY;
        }

        return 0;
    }

    public function add_headline($headline_id)
    {
        // already exist
        if ($this->_lid_exist) {
            return $this->_lid_exist;
        }

        $uid      = $this->get_xoops_uid();
        $url      = $this->get_post_url($_POST, 'headline_url');
        $encoding = $this->get_post_encoding($_POST['headline_encoding']);

        // link table
        $link_obj = $this->_rssc_link_xmlHandler->create();
        $link_obj->setVar('uid', $uid);
        $link_obj->setVar('url', $url);
        $link_obj->setVar('encoding', $encoding);
        $link_obj->setVar('mode', $this->_xml_mode);
        $link_obj->setVar('rdf_url', $this->_rdf_url);
        $link_obj->setVar('rss_url', $this->_rss_url);
        $link_obj->setVar('atom_url', $this->_atom_url);
        $link_obj->setVar('title', $_POST['headline_name']);
        $link_obj->setVar('refresh', $_POST['headline_cachetime']);
        $link_obj->setVar('mid', $this->_mid);
        $link_obj->setVar('p1', $headline_id);

        $newid = $this->_rssc_link_xmlHandler->insert($link_obj);
        if (!$newid) {
            $this->_set_errors($this->_rssc_link_xmlHandler->getErrors());

            return false;
        }

        return $newid;
    }

    public function refresh_for_add_headline($rssc_lid)
    {
        $this->_rssc_refreshHandler->setPriorityRssAtom(RSSC_C_SEL_RSS);
        $this->_rssc_refreshHandler->set_force_refresh(1);
        $ret = $this->_rssc_refreshHandler->refresh_link_for_add_link($rssc_lid);
        switch ($ret) {
            case RSSC_CODE_PARSE_MSG:
                $this->_parse_result = $this->_rssc_refreshHandler->get_parse_result();
                break;
            case RSSC_CODE_DB_ERROR:
            case RSSC_CODE_PARSE_FAILED:
            case RSSC_CODE_REFRESH_ERROR:
                $this->_set_errors($this->_rssc_refreshHandler->getErrors());
                break;
        }

        return $ret;
    }

    public function get_parse_result()
    {
        return $this->_parse_result;
    }

    public function check_mod_form_headline($rssc_lid, $url, $rss_url)
    {
        $this->_clear_errors();

        // not exist link
        if (!$this->_rssc_link_xmlHandler->is_exist($rssc_lid)) {
            return RSSC_CODE_LINK_NOT_EXIST;
        }

        $sel      = RSSC_C_SEL_RSS;
        $rdf_url  = '';
        $atom_url = '';

        if ($rss_url && ('https://' != $rss_url)) {
            $mode = RSSC_C_MODE_RSS;
        } else {
            $mode = RSSC_C_MODE_AUTO;
        }

        // discover xml link when auto mode
        $ret = $this->_rssc_utility->discover_for_manage($mode, $url, $rdf_url, $rss_url, $atom_url, $sel);
        if (RSSC_CODE_DISCOVER_FAILED == $ret) {
            $this->_set_errors($this->_rssc_utility->getErrors());

            return $ret;
        }

        $this->_xml_mode = $this->_rssc_utility->get_xml_mode();
        $this->_rdf_url  = $this->_rssc_utility->get_rdf_url();
        $this->_rss_url  = $this->_rssc_utility->get_rss_url();
        $this->_atom_url = $this->_rssc_utility->get_atom_url();

        // check exist rss link
        $list = $this->_rssc_link_xmlHandler->get_list_by_rssurl($this->_rdf_url, $this->_rss_url, $this->_atom_url, $rssc_lid);
        if ($list) {
            $script = XOOPS_URL . '/modules/' . RSSC_HEADLINE_RSSC_DIRNAME . '/admin/';
            $script .= 'link_manage.php?op=mod_form&amp;lid=';
            $msg    = $this->_rssc_link_xmlHandler->build_error_rssurl_list($list, $script);
            $this->_set_errors($msg);

            return RSSC_CODE_LINK_ALREADY;
        }

        return 0;
    }

    public function mod_headline($rssc_lid)
    {
        $link_obj = &$this->_rssc_link_xmlHandler->get($rssc_lid);
        if (!is_object($link_obj)) {
            return false;
        }

        $url      = $this->get_post_url($_POST, 'headline_url');
        $rss_url  = $this->get_post_url($_POST, 'headline_rssurl');
        $encoding = $this->get_post_encoding($_POST['headline_encoding']);

        if ($url && ('https://' != $url)) {
            $link_obj->setVar('url', $url);
        }

        if ($rss_url && ('https://' != $rss_url)) {
            $mode = RSSC_C_MODE_RSS;
            $link_obj->setVar('rss_url', $rss_url);
            $link_obj->setVar('mode', RSSC_C_MODE_RSS);
        }

        $link_obj->setVar('encoding', $encoding);
        $link_obj->setVar('title', $_POST['headline_name']);
        $link_obj->setVar('refresh', $_POST['headline_cachetime']);

        if (!$this->_rssc_link_xmlHandler->update($link_obj)) {
            $this->_set_errors($this->_rssc_link_xmlHandler->getErrors());

            return false;
        }

        return true;
    }

    public function del_headline($rssc_lid)
    {
        $link_obj = &$this->_rssc_link_xmlHandler->get($rssc_lid);
        if (!is_object($link_obj)) {
            return false;
        }

        if (!$this->_rssc_link_xmlHandler->delete($link_obj)) {
            $this->_set_errors($this->_rssc_link_xmlHandler->getErrors());

            return false;
        }

        return true;
    }

    public function update_headline($rssc_lid, $i)
    {
        $link_obj = &$this->_rssc_link_xmlHandler->get($rssc_lid);
        if (!is_object($link_obj)) {
            return false;
        }

        $encoding = $this->get_post_encoding($_POST['headline_encoding'][$i]);

        $link_obj->setVar('encoding', $encoding);
        $link_obj->setVar('refresh', $_POST['headline_cachetime'][$i]);

        if (!$this->_rssc_link_xmlHandler->update($link_obj)) {
            $this->_set_errors($this->_rssc_link_xmlHandler->getErrors());

            return false;
        }

        return true;
    }

    //---------------------------------------------------------
    // linkHandler object
    //---------------------------------------------------------
    public function &get($rssc_lid)
    {
        $this->_rssc_link_obj = &$this->_rssc_link_xmlHandler->get($rssc_lid);

        return $this->_rssc_link_obj;
    }

    public function get_cache_var($key, $format = 's')
    {
        if (!is_object($this->_rssc_link_obj)) {
            return false;
        }

        switch ($key) {
            case 'rssurl':
                $val = $this->_rssc_link_obj->get_rssurl_by_mode($format);

                return $val;
            case 'cachetime':
                $key = 'cachetime';
                break;
        }

        $val = $this->_rssc_link_obj->getVar($key, $format);

        return $val;
    }

    //---------------------------------------------------------
    // feedHandler
    //---------------------------------------------------------
    public function get_feed_count($rssc_lid)
    {
        return $this->_rssc_viewHandler->get_feed_count_by_lid($rssc_lid);
    }

    //---------------------------------------------------------
    // POST
    //---------------------------------------------------------
    public function get_post_url($array, $key)
    {
        if (isset($array[$key]) && ('https://' != $array[$key])) {
            return $array[$key];
        }

        return '';
    }

    public function get_post_encoding($value)
    {
        if ('auto' == $value) {
            return '';
        }

        return $value;
    }

    public function get_xoops_uid()
    {
        global $xoopsUser;

        $uid = 0;
        if (is_object($xoopsUser)) {
            $uid = $xoopsUser->getVar('uid');
        }

        return $uid;
    }

    public function set_mid($value)
    {
        $this->_mid = (int)$value;
    }

    // --- class end ---
}
