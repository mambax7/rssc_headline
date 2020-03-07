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
if ( file_exists(XOOPS_ROOT_PATH.'/modules/'.RSSC_HEADLINE_RSSC_DIRNAME.'/api/rssc_headline.php') )
{
	include_once XOOPS_ROOT_PATH.'/modules/'.RSSC_HEADLINE_RSSC_DIRNAME.'/api/rssc_headline.php';
}
else
{
	die('require RSSC module');
}

//=========================================================
// class ahl rssc handler
// 2005-10-20 K.OHWADA
//=========================================================
class rssc_headlineRsscHandler extends rssc_error
{
// config constant
	var $FORMAT_DATE = 'l';	// l=long, r=rfc822

// handler
	var $_rssc_refresh_handler;
	var $_rssc_view_handler;
	var $_rssc_link_xml_handler;
	var $_rssc_utility;

// set variable
	var $_mid;

// result
	var $_result;
	var $_lid_exist = 0;
	var $_xml_mode  = 0;
	var $_rdf_url;
	var $_rss_url;
	var $_atom_url;
	var $_parse_result;

	var $_rssc_link_obj;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function rssc_headlineRsscHandler()
{
	$this->_rssc_refresh_handler    =& rssc_get_handler('refresh',    RSSC_HEADLINE_RSSC_DIRNAME );
	$this->_rssc_view_handler       =& rssc_get_handler('view',       RSSC_HEADLINE_RSSC_DIRNAME );
	$this->_rssc_link_xml_handler   =& rssc_get_handler('link_xml',   RSSC_HEADLINE_RSSC_DIRNAME );
	$this->_rssc_utility            =& rssc_xml_utility::getInstance();

	global $xoopsModule;
	if ( is_object($xoopsModule) )
	{
		$this->set_mid( $xoopsModule->getVar('mid') );
	}
}

//=========================================================
// for class rssc_headline_Renderer
//=========================================================
function update_cache( $rssc_lid )
{
	$this->_rssc_refresh_handler->setPriorityRssAtom( RSSC_C_SEL_RSS );
	return $this->_rssc_refresh_handler->refresh($rssc_lid);
}

function fetch_cache($rssc_lid)
{
	$this->_rssc_view_handler->set_max_title( -1 );	// unlimited
	$this->_rssc_view_handler->set_max_content( -1 );
	$this->_result =& $this->_rssc_view_handler->get_sanitized_store_by_lid($rssc_lid);
}

function &get_channel()
{
	$channel_obj =& $this->_rssc_view_handler->create_channel();
	$channel_obj->set_vars( $this->_get_result('channel') );
	$channel_obj->format_for_rss( $this->FORMAT_DATE );
	$arr =& $channel_obj->get_vars();
	return $arr;
}

function &get_image()
{
	return $this->_get_result('image');
}

function &get_items()
{
	$ret = false;
	$items = $this->_get_result('items');

	if ($items)
	{
		$items_obj =& $this->_rssc_view_handler->create_items();
		$items_obj->set_vars( $items );
		$items_obj->format_for_rss( $this->FORMAT_DATE );
		$ret =& $items_obj->get_vars();
	}
	return $ret;
}

function &_get_result($key)
{
	$ret = false;
	if ( $this->_result[$key] )
	{
		$ret = $this->_result[$key];
	}
	return $ret;
}

function set_force_update( $force_update=false )
{
	$this->_rssc_refresh_handler->set_force_refresh( $force_update );
}

function set_max_num_feed($value)
{
	$this->_rssc_view_handler->setFeedLimit($value);
}

//=========================================================
// for admin/index.php
//=========================================================
function check_add_headline()
{
	$this->_clear_errors();

	$sel      = RSSC_C_SEL_RSS;
	$url      = $this->get_post_url($_POST, 'headline_url');
	$rss_url  = $this->get_post_url($_POST, 'headline_rssurl');
	$rdf_url  = '';
	$atom_url = '';

	if ( $rss_url && ($rss_url != 'http://') )
	{
		$mode = RSSC_C_MODE_RSS;
	}
	else
	{
		$mode = RSSC_C_MODE_AUTO;
	}

// discover xml link when auto mode
	$ret = $this->_rssc_utility->discover_for_manage( $mode, $url, $rdf_url, $rss_url, $atom_url, $sel );
	if ( $ret == RSSC_CODE_DISCOVER_FAILED )
	{
		$this->_set_errors( $this->_rssc_utility->getErrors() );
		return $ret;
	}

	$this->_xml_mode = $this->_rssc_utility->get_xml_mode();
	$this->_rdf_url  = $this->_rssc_utility->get_rdf_url();
	$this->_rss_url  = $this->_rssc_utility->get_rss_url();
	$this->_atom_url = $this->_rssc_utility->get_atom_url();

// check exist rss link
	$list = $this->_rssc_link_xml_handler->get_list_by_rssurl($this->_rdf_url, $this->_rss_url, $this->_atom_url);
	if ( $list )
	{
		$this->_set_errors( _RSSC_LINK_ALREADY );
		$script  = XOOPS_URL.'/modules/'.RSSC_HEADLINE_RSSC_DIRNAME.'/admin/';
		$script .= 'link_manage.php?op=mod_form&amp;lid=';
		$mag     = $this->_rssc_link_xml_handler->build_error_rssurl_list($list, $script);
		$this->_set_errors( $mag );
		$this->_lid_exist = $list[0];
		return RSSC_CODE_LINK_ALREADY;
	}

	return 0;
}

function add_headline( $headline_id )
{
// already exist
	if ( $this->_lid_exist )
	{
		return $this->_lid_exist;
	}

	$uid      = $this->get_xoops_uid();
	$url      = $this->get_post_url($_POST, 'headline_url');
	$encoding = $this->get_post_encoding( $_POST['headline_encoding'] );

// link table
	$link_obj = $this->_rssc_link_xml_handler->create();
	$link_obj->setVar('uid',       $uid);
	$link_obj->setVar('url',       $url);
	$link_obj->setVar('encoding',  $encoding);
	$link_obj->setVar('mode',      $this->_xml_mode);
	$link_obj->setVar('rdf_url',   $this->_rdf_url);
	$link_obj->setVar('rss_url',   $this->_rss_url);
	$link_obj->setVar('atom_url',  $this->_atom_url);
	$link_obj->setVar('title',     $_POST['headline_name']);
	$link_obj->setVar('refresh',   $_POST['headline_cachetime']);
	$link_obj->setVar('mid', $this->_mid);
	$link_obj->setVar('p1',  $headline_id);

	$newid = $this->_rssc_link_xml_handler->insert($link_obj);
	if ( !$newid ) 
	{
		$this->_set_errors( $this->_rssc_link_xml_handler->getErrors() );
		return false;
	}

	return $newid;
}

function refresh_for_add_headline( $rssc_lid )
{
	$this->_rssc_refresh_handler->setPriorityRssAtom( RSSC_C_SEL_RSS );
	$this->_rssc_refresh_handler->set_force_refresh(1);
	$ret = $this->_rssc_refresh_handler->refresh_link_for_add_link( $rssc_lid );
	switch ( $ret )
	{
		case RSSC_CODE_PARSE_MSG:
			$this->_parse_result = $this->_rssc_refresh_handler->get_parse_result();
			break;

		case RSSC_CODE_DB_ERROR:
		case RSSC_CODE_PARSE_FAILED:
		case RSSC_CODE_REFRESH_ERROR:
			$this->_set_errors( $this->_rssc_refresh_handler->getErrors() );
			break;
	}
	return $ret;
}

function get_parse_result()
{
	return $this->_parse_result;
}

function check_mod_form_headline($rssc_lid, $url, $rss_url)
{
	$this->_clear_errors();

// not exist link
	if ( !$this->_rssc_link_xml_handler->is_exist($rssc_lid) )
	{
		return RSSC_CODE_LINK_NOT_EXIST;
	}

	$sel      = RSSC_C_SEL_RSS;
	$rdf_url  = '';
	$atom_url = '';

	if ( $rss_url && ($rss_url != 'http://') )
	{
		$mode = RSSC_C_MODE_RSS;
	}
	else
	{
		$mode = RSSC_C_MODE_AUTO;
	}

// discover xml link when auto mode
	$ret = $this->_rssc_utility->discover_for_manage( $mode, $url, $rdf_url, $rss_url, $atom_url, $sel );
	if ( $ret == RSSC_CODE_DISCOVER_FAILED )
	{
		$this->_set_errors( $this->_rssc_utility->getErrors() );
		return $ret;
	}

	$this->_xml_mode = $this->_rssc_utility->get_xml_mode();
	$this->_rdf_url  = $this->_rssc_utility->get_rdf_url();
	$this->_rss_url  = $this->_rssc_utility->get_rss_url();
	$this->_atom_url = $this->_rssc_utility->get_atom_url();

// check exist rss link
	$list = $this->_rssc_link_xml_handler->get_list_by_rssurl($this->_rdf_url, $this->_rss_url, $this->_atom_url, $rssc_lid);
	if ( $list )
	{
		$script  = XOOPS_URL.'/modules/'.RSSC_HEADLINE_RSSC_DIRNAME.'/admin/';
		$script .= 'link_manage.php?op=mod_form&amp;lid=';
		$msg     = $this->_rssc_link_xml_handler->build_error_rssurl_list($list, $script);
		$this->_set_errors( $msg );
		return RSSC_CODE_LINK_ALREADY;
	}

	return 0;
}

function mod_headline( $rssc_lid )
{
	$link_obj =& $this->_rssc_link_xml_handler->get($rssc_lid);
	if ( !is_object($link_obj) )
	{
		return false;
	}

	$url      = $this->get_post_url($_POST, 'headline_url');
	$rss_url  = $this->get_post_url($_POST, 'headline_rssurl');
	$encoding = $this->get_post_encoding( $_POST['headline_encoding'] );

	if ( $url && ($url != 'http://') )
	{
		$link_obj->setVar('url', $url);
	}

	if ( $rss_url && ($rss_url != 'http://') )
	{
		$mode = RSSC_C_MODE_RSS;
		$link_obj->setVar('rss_url', $rss_url);
		$link_obj->setVar('mode',    RSSC_C_MODE_RSS);
	}

	$link_obj->setVar('encoding',  $encoding);
	$link_obj->setVar('title',     $_POST['headline_name']);
	$link_obj->setVar('refresh',   $_POST['headline_cachetime']);

	if ( !$this->_rssc_link_xml_handler->update($link_obj) ) 
	{
		$this->_set_errors( $this->_rssc_link_xml_handler->getErrors() );
		return false;
	}

	return true;
}

function del_headline( $rssc_lid )
{
	$link_obj =& $this->_rssc_link_xml_handler->get($rssc_lid);
	if ( !is_object($link_obj) )
	{
		return false;
	}

	if ( !$this->_rssc_link_xml_handler->delete($link_obj) ) 
	{
		$this->_set_errors( $this->_rssc_link_xml_handler->getErrors() );
		return false;
	}

	return true;
}

function update_headline( $rssc_lid, $i )
{
	$link_obj =& $this->_rssc_link_xml_handler->get($rssc_lid);
	if ( !is_object($link_obj) )
	{
		return false;
	}

	$encoding = $this->get_post_encoding( $_POST['headline_encoding'][$i] );

	$link_obj->setVar('encoding',  $encoding);
	$link_obj->setVar('refresh',   $_POST['headline_cachetime'][$i] );

	if ( !$this->_rssc_link_xml_handler->update($link_obj) ) 
	{
		$this->_set_errors( $this->_rssc_link_xml_handler->getErrors() );
		return false;
	}

	return true;
}

//---------------------------------------------------------
// link_handler object
//---------------------------------------------------------
function &get( $rssc_lid )
{
	$this->_rssc_link_obj =& $this->_rssc_link_xml_handler->get($rssc_lid);
	return $this->_rssc_link_obj;
}

function get_cache_var( $key, $format='s' )
{
	if ( !is_object($this->_rssc_link_obj) )
	{	return false;	}

	switch ($key)
	{
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
// feed_handler
//---------------------------------------------------------
function get_feed_count( $rssc_lid )
{
	return $this->_rssc_view_handler->get_feed_count_by_lid($rssc_lid);
}

//---------------------------------------------------------
// POST
//---------------------------------------------------------
function get_post_url($array, $key)
{
	if ( isset($array[$key]) && ( $array[$key] != 'http://' ) )
	{
		return $array[$key];
	}

	return '';
}

function get_post_encoding( $value )
{
	if ($value == 'auto')
	{
		return '';
	}

	return $value;
}

function get_xoops_uid()
{
	global $xoopsUser;

	$uid = 0;
	if ( is_object($xoopsUser) )
	{
		$uid = $xoopsUser->getVar('uid');
	}
	return $uid;
}

function set_mid($value)
{
	$this->_mid = intval($value);
}


// --- class end ---
}

?>