<?php

namespace XoopsModules\Rssheadline;

// $Id: headlinerenderer.php,v 1.1 2011/12/29 14:41:32 ohwada Exp $

// 2007-08-01 K.OHWADA
// main.php

// 2006-07-02 K.OHWADA
// change _HL_xx to _MD_RSSHEADLINE__xx
// change xoopsheadline to rssheadline
// change XoopsHeadline to rssheadline

//=========================================================
// RSSC HeadLine
// 2006-07-02 K.OHWADA
//=========================================================
// based on xoopsHeadline
// Id: headlinerenderer.php,v 1.3 2005/08/03 12:40:01 onokazu Exp
//=========================================================

require_once XOOPS_ROOT_PATH . '/class/template.php';

$XOOPS_LANGUAGE = $GLOBALS['xoopsConfig']['language'];

// main.php
//if (file_exists(XOOPS_ROOT_PATH . '/modules/rssheadline/language/' . $XOOPS_LANGUAGE . '/main.php')) {
//    require_once XOOPS_ROOT_PATH . '/modules/rssheadline/language/' . $XOOPS_LANGUAGE . '/main.php';
//} else {
//    require_once XOOPS_ROOT_PATH . '/modules/rssheadline/language/english/main.php';
//}

xoops_loadLanguage('main');

/**
 * Class Renderer
 * @package XoopsModules\Rssheadline
 */
class Renderer
{
    // holds reference to rssheadline class object
    public $_hl;

    // XoopTemplate object
    public $_tpl;

    public $_feed;

    public $_block;

    public $_errors = [];

    // RSS2 SAX parser
    public $_parser;

    // --- define rssc handler ---
    public $_rsscHandler;
    public $_rssc_lid;

    // ---

    /**
     * Renderer constructor.
     * @param $headline
     */
    public function __construct(&$headline)
    {
        $this->_hl  = &$headline;
        $this->_tpl = new \XoopsTpl();

        // --- define rssc handler ---
        $this->_rsscHandler = xoops_getModuleHandler('rssc', 'rssheadline');
        $this->_rssc_lid    = $this->_hl->getVar('headline_rssc_lid');
        // ---
    }

    // --- not use ---

    /**
     * @return bool|void
     */
    public function xxx_updateCache()
    {
        if (!$fp = fopen($this->_hl->getVar('headline_rssurl'), 'rb')) {
            $this->_setErrors('Could not open file: ' . $this->_hl->getVar('headline_rssurl'));

            return false;
        }
        $data = '';
        while (!feof($fp)) {
            $data .= fgets($fp, 4096);
        }
        fclose($fp);
        $this->_hl->setVar('headline_xml', $this->convertToUtf8($data));
        $this->_hl->setVar('headline_updated', time());
        $headlineHandler = xoops_getModuleHandler('headline', 'rssheadline');

        return $headlineHandler->insert($this->_hl);
    }

    // ---

    /**
     * @param bool $force_update
     * @return bool
     */
    public function renderFeed($force_update = false)
    {
        // --- update & fetch cache ---
        //		if ($force_update || $this->_hl->cacheExpired()) {
        //			if (!$this->updateCache()) {
        //				return false;
        //			}
        //		}
        //		if (!$this->_parse()) {
        //			return false;
        //		}

        $this->_rsscHandler->set_force_update($force_update);
        $this->_rsscHandler->update_cache($this->_rssc_lid);

        $this->_rsscHandler->set_max_num_feed($this->_hl->getVar('headline_mainmax'));
        $this->_rsscHandler->fetch_cache($this->_rssc_lid);
        $channel_data = $this->_rsscHandler->get_channel();
        $image_data   = $this->_rsscHandler->get_image();
        $items        = $this->_rsscHandler->get_items();
        // ---

        $this->_tpl->clear_all_assign();
        $this->_tpl->assign('xoops_url', XOOPS_URL);

        // ---
        //		$channel_data =& $this->_parser->getChannelData();
        //		array_walk($channel_data, array($this, 'convertFromUtf8'));
        // ---

        $this->_tpl->assign_by_ref('channel', $channel_data);
        if (1 == $this->_hl->getVar('headline_mainimg')) {
            // ---
            //			$image_data =& $this->_parser->getImageData();
            //			array_walk($image_data, array($this, 'convertFromUtf8'));
            // ---

            $this->_tpl->assign_by_ref('image', $image_data);
        }
        if (1 == $this->_hl->getVar('headline_mainfull')) {
            $this->_tpl->assign('show_full', true);
        } else {
            $this->_tpl->assign('show_full', false);
        }

        // ---
        //		$items =& $this->_parser->getItems();
        // ---

        $count = count($items);
        $max   = ($count > $this->_hl->getVar('headline_mainmax')) ? $this->_hl->getVar('headline_mainmax') : $count;
        for ($i = 0; $i < $max; $i++) {
            // ---
            //			array_walk($items[$i], array($this, 'convertFromUtf8'));
            // ---

            $this->_tpl->append_by_ref('items', $items[$i]);
        }
        $this->_tpl->assign(
            [
                'lang_lastbuild'   => _MD_RSSHEADLINE__LASTBUILD,
                'lang_language'    => _MD_RSSHEADLINE__LANGUAGE,
                'lang_description' => _MD_RSSHEADLINE__DESCRIPTION,
                'lang_webmaster'   => _MD_RSSHEADLINE__WEBMASTER,
                'lang_category'    => _MD_RSSHEADLINE__CATEGORY,
                'lang_generator'   => _MD_RSSHEADLINE__GENERATOR,
                'lang_title'       => _MD_RSSHEADLINE__TITLE,
                'lang_pubdate'     => _MD_RSSHEADLINE__PUBDATE,
                'lang_description' => _MD_RSSHEADLINE__DESCRIPTION,
                'lang_more'        => _MORE,
            ]
        );
        $this->_feed = $this->_tpl->fetch('db:rssheadline_feed.html');

        return true;
    }

    /**
     * @param bool $force_update
     * @return bool
     */
    public function renderBlock($force_update = false)
    {
        // --- update & fetch cache ---
        //		if ($force_update || $this->_hl->cacheExpired()) {
        //			if (!$this->updateCache()) {
        //				return false;
        //			}
        //		}
        //		if (!$this->_parse()) {
        //			return false;
        //		}

        $this->_rsscHandler->set_force_update($force_update);
        $this->_rsscHandler->update_cache($this->_rssc_lid);

        $this->_rsscHandler->set_max_num_feed($this->_hl->getVar('headline_blockmax'));
        $this->_rsscHandler->fetch_cache($this->_rssc_lid);

        $channel_data = $this->_rsscHandler->get_channel();
        $image_data   = $this->_rsscHandler->get_image();
        $items        = $this->_rsscHandler->get_items();
        // ---

        $this->_tpl->clear_all_assign();
        $this->_tpl->assign('xoops_url', XOOPS_URL);

        // ---
        //		$channel_data =& $this->_parser->getChannelData();
        //		array_walk($channel_data, array($this, 'convertFromUtf8'));
        // ---

        $this->_tpl->assign_by_ref('channel', $channel_data);
        if (1 == $this->_hl->getVar('headline_blockimg')) {
            // ---
            //			$image_data =& $this->_parser->getImageData();
            //			array_walk($image_data, array($this, 'convertFromUtf8'));
            // ---

            $this->_tpl->assign_by_ref('image', $image_data);
        }

        // ---
        //		$items =& $this->_parser->getItems();
        // ---

        $count = count($items);
        $max   = ($count > $this->_hl->getVar('headline_blockmax')) ? $this->_hl->getVar('headline_blockmax') : $count;
        for ($i = 0; $i < $max; $i++) {
            // ---
            //			array_walk($items[$i], array($this, 'convertFromUtf8'));
            // ---

            $this->_tpl->append_by_ref('items', $items[$i]);
        }
        $this->_tpl->assign(['site_name' => $this->_hl->getVar('headline_name'), 'site_url' => $this->_hl->getVar('headline_url'), 'site_id' => $this->_hl->getVar('headline_id')]);
        $this->_block = $this->_tpl->fetch('file:' . XOOPS_ROOT_PATH . '/modules/rssheadline/blocks/headline_block.html');

        return true;
    }

    // --- not use ---

    /**
     * @return bool
     */
    public function xxx_parse()
    {
        if (isset($this->_parser)) {
            return true;
        }
        require_once XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php';
        $this->_parser = new \XoopsXmlRss2Parser($this->_hl->getVar('headline_xml'));
        switch ($this->_hl->getVar('headline_encoding')) {
            case 'utf-8':
                $this->_parser->useUtfEncoding();
                break;
            case 'us-ascii':
                $this->_parser->useAsciiEncoding();
                break;
            default:
                $this->_parser->useIsoEncoding();
                break;
        }
        $result = $this->_parser->parse();
        if (!$result) {
            $this->_setErrors($this->_parser->getErrors(false));
            unset($this->_parser);

            return false;
        }

        return true;
    }

    // ---

    public function &getFeed()
    {
        return $this->_feed;
    }

    public function &getBlock()
    {
        return $this->_block;
    }

    /**
     * @param $err
     */
    public function _setErrors($err)
    {
        $this->_errors[] = $err;
    }

    /**
     * @param bool $ashtml
     * @return array|string
     */
    public function &getErrors($ashtml = true)
    {
        if (!$ashtml) {
            return $this->_errors;
        }
        $ret = '';
        if (count($this->_errors) > 0) {
            foreach ($this->_errors as $error) {
                $ret .= $error . '<br>';
            }
        }

        return $ret;
    }

    // abstract
    // overide this method in /language/your_language/headlinerenderer.php
    // this method is called by the array_walk function
    // return void
    /**
     * @param $value
     * @param $key
     */
    public function convertFromUtf8(&$value, $key)
    {
    }

    // abstract
    // overide this method in /language/your_language/headlinerenderer.php
    // return string
    /**
     * @param $xmlfile
     * @return mixed
     */
    public function &convertToUtf8($xmlfile)
    {
        return $xmlfile;
    }
}
