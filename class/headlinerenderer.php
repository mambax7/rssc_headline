<?php
// $Id: headlinerenderer.php,v 1.1 2011/12/29 14:41:32 ohwada Exp $

// 2007-08-01 K.OHWADA
// main.php

// 2006-07-02 K.OHWADA
// change _HL_xx to _RSSC_HEADLINE_xx
// change xoopsheadline to rssc_headline
// change XoopsHeadline to rssc_headline

//=========================================================
// RSSC HeadLine
// 2006-07-02 K.OHWADA
//=========================================================
// based on xoopsHeadline
// Id: headlinerenderer.php,v 1.3 2005/08/03 12:40:01 onokazu Exp
//=========================================================

include_once XOOPS_ROOT_PATH.'/class/template.php';

$XOOPS_LANGUAGE = $GLOBALS['xoopsConfig']['language'];

// main.php
if ( file_exists(XOOPS_ROOT_PATH.'/modules/rssc_headline/language/'.$XOOPS_LANGUAGE.'/main.php') ) 
{
	include_once XOOPS_ROOT_PATH.'/modules/rssc_headline/language/'.$XOOPS_LANGUAGE.'/main.php';
}
else
{
	include_once XOOPS_ROOT_PATH.'/modules/rssc_headline/language/english/main.php';
}

class rssc_headline_Renderer
{
	// holds reference to rssc_headline class object
	var $_hl;

	// XoopTemplate object
	var $_tpl;

	var $_feed;

	var $_block;

	var $_errors = array();

	// RSS2 SAX parser
	var $_parser;

// --- define rssc handler ---
	var $_rssc_handler;
	var $_rssc_lid;
// ---

	function rssc_headline_Renderer(&$headline)
	{
		$this->_hl =& $headline;
		$this->_tpl = new XoopsTpl();

// --- define rssc handler ---
		$this->_rssc_handler = & xoops_getmodulehandler('rssc', 'rssc_headline');
		$this->_rssc_lid     = $this->_hl->getVar('headline_rssc_lid');
// ---
	}

// --- not use ---
	function xxx_updateCache()
	{
		if (!$fp = fopen($this->_hl->getVar('headline_rssurl'), 'r')) {
			$this->_setErrors('Could not open file: '.$this->_hl->getVar('headline_rssurl'));
			return false;
		}
		$data = '';
		while (!feof ($fp)) {
			$data .= fgets($fp, 4096);
		}
		fclose ($fp);
		$this->_hl->setVar('headline_xml', $this->convertToUtf8($data));
		$this->_hl->setVar('headline_updated', time());
		$headline_handler =& xoops_getmodulehandler('headline', 'rssc_headline');
		return $headline_handler->insert($this->_hl);
	}
// ---

	function renderFeed($force_update = false)
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

		$this->_rssc_handler->set_force_update( $force_update );
		$this->_rssc_handler->update_cache( $this->_rssc_lid );

		$this->_rssc_handler->set_max_num_feed( $this->_hl->getVar('headline_mainmax') );
		$this->_rssc_handler->fetch_cache( $this->_rssc_lid );
		$channel_data = $this->_rssc_handler->get_channel();
		$image_data   = $this->_rssc_handler->get_image();
		$items        = $this->_rssc_handler->get_items();
// ---

		$this->_tpl->clear_all_assign();
		$this->_tpl->assign('xoops_url', XOOPS_URL);

// ---
//		$channel_data =& $this->_parser->getChannelData();
//		array_walk($channel_data, array($this, 'convertFromUtf8'));
// ---

		$this->_tpl->assign_by_ref('channel', $channel_data);
		if ($this->_hl->getVar('headline_mainimg') == 1) {

// ---
//			$image_data =& $this->_parser->getImageData();
//			array_walk($image_data, array($this, 'convertFromUtf8'));
// ---

			$this->_tpl->assign_by_ref('image', $image_data);
		}
		if ($this->_hl->getVar('headline_mainfull') == 1) {
			$this->_tpl->assign('show_full', true);
		} else {
			$this->_tpl->assign('show_full', false);
		}

// ---
//		$items =& $this->_parser->getItems();
// ---

		$count = count($items);
		$max = ($count > $this->_hl->getVar('headline_mainmax')) ? $this->_hl->getVar('headline_mainmax') : $count;
		for ($i = 0; $i < $max; $i++) {

// ---
//			array_walk($items[$i], array($this, 'convertFromUtf8'));
// ---

			$this->_tpl->append_by_ref('items', $items[$i]);
		}
		$this->_tpl->assign(array('lang_lastbuild' => _RSSC_HEADLINE_LASTBUILD, 'lang_language' => _RSSC_HEADLINE_LANGUAGE, 'lang_description' => _RSSC_HEADLINE_DESCRIPTION, 'lang_webmaster' => _RSSC_HEADLINE_WEBMASTER, 'lang_category' => _RSSC_HEADLINE_CATEGORY, 'lang_generator' => _RSSC_HEADLINE_GENERATOR, 'lang_title' => _RSSC_HEADLINE_TITLE, 'lang_pubdate' => _RSSC_HEADLINE_PUBDATE, 'lang_description' => _RSSC_HEADLINE_DESCRIPTION, 'lang_more' => _MORE));
		$this->_feed =& $this->_tpl->fetch('db:rssc_headline_feed.html');
		return true;
	}

	function renderBlock($force_update = false)
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

		$this->_rssc_handler->set_force_update( $force_update );
		$this->_rssc_handler->update_cache( $this->_rssc_lid );

		$this->_rssc_handler->set_max_num_feed( $this->_hl->getVar('headline_blockmax') );
		$this->_rssc_handler->fetch_cache( $this->_rssc_lid );

		$channel_data = $this->_rssc_handler->get_channel();
		$image_data   = $this->_rssc_handler->get_image();
		$items        = $this->_rssc_handler->get_items();
// ---

		$this->_tpl->clear_all_assign();
		$this->_tpl->assign('xoops_url', XOOPS_URL);

// ---
//		$channel_data =& $this->_parser->getChannelData();
//		array_walk($channel_data, array($this, 'convertFromUtf8'));
// ---

		$this->_tpl->assign_by_ref('channel', $channel_data);
		if ($this->_hl->getVar('headline_blockimg') == 1) {

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
		$max = ($count > $this->_hl->getVar('headline_blockmax')) ? $this->_hl->getVar('headline_blockmax') : $count;
		for ($i = 0; $i < $max; $i++) {

// ---
//			array_walk($items[$i], array($this, 'convertFromUtf8'));
// ---

			$this->_tpl->append_by_ref('items', $items[$i]);
		}
		$this->_tpl->assign(array('site_name' => $this->_hl->getVar('headline_name'), 'site_url' => $this->_hl->getVar('headline_url'), 'site_id' => $this->_hl->getVar('headline_id')));
		$this->_block =& $this->_tpl->fetch('file:'.XOOPS_ROOT_PATH.'/modules/rssc_headline/blocks/headline_block.html');
		return true;
	}

// --- not use ---
	function xxx_parse()
	{
		if (isset($this->_parser)) {
			return true;
		}
		include_once XOOPS_ROOT_PATH.'/class/xml/rss/xmlrss2parser.php';
		$this->_parser = new XoopsXmlRss2Parser($this->_hl->getVar('headline_xml'));
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

	function &getFeed()
	{
		return $this->_feed;
	}

	function &getBlock()
	{
		return $this->_block;
	}

	function _setErrors($err)
	{
		$this->_errors[] = $err;
	}

	function &getErrors($ashtml = true)
	{
		if (!$ashtml) {
			return $this->_errors;
		} else {
		$ret = '';
		if (count($this->_errors) > 0) {
			foreach ($this->_errors as $error) {
				$ret .= $error.'<br />';
			}
		}
		return $ret;
		}
	}

	// abstract
	// overide this method in /language/your_language/headlinerenderer.php
	// this method is called by the array_walk function
	// return void
	function convertFromUtf8(&$value, $key)
	{
	}

	// abstract
	// overide this method in /language/your_language/headlinerenderer.php
	// return string
	function &convertToUtf8(&$xmlfile)
	{
		return $xmlfile;
	}
}
?>