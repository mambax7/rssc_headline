<?php
// $Id: headline.php,v 1.1 2011/12/29 14:41:32 ohwada Exp $

// 2006-07-02 K.OHWADA
// change HL_xx to RSSC_HEADLINE_xx
// change xoopsheadline to rssc_sheadline

//=========================================================
// RSSC HeadLine
// 2006-07-02 K.OHWADA
//=========================================================
// based on xoopsHeadline
// Id: headline.php,v 1.2 2005/03/18 12:52:49 onokazu Exp
//=========================================================

include_once XOOPS_ROOT_PATH.'/modules/rssc_headline/include/functions.php';

function b_rssc_headline_show($options)
{
	global $xoopsConfig;
	$block = array();
	$hlman =& xoops_getmodulehandler('headline', 'rssc_headline');
	$headlines =& $hlman->getObjects(new Criteria('headline_asblock', 1));
	$count = count($headlines);
	for ($i = 0; $i < $count; $i++) {
		$renderer =& rssc_headline_getrenderer($headlines[$i]);
		if (!$renderer->renderBlock()) {
			if ($xoopsConfig['debug_mode'] == 2) {
				$block['feeds'][] = sprintf(_RSSC_HEADLINE_FAILGET, $headlines[$i]->getVar('headline_name')).'<br />'.$renderer->getErrors();
			}
			continue;
		}
		$block['feeds'][] = $renderer->getBlock();
	}
	return $block;
}
?>