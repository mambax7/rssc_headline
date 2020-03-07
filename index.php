<?php
// $Id: index.php,v 1.1 2011/12/29 14:41:31 ohwada Exp $

// 2006-07-02 K.OHWADA
// change _HL_xx to _RSSC_HEADLINE_xx
// change xoopsheadline to rssc_headline
// Fatal error: Call to a member function getVar() on a non-object

//=========================================================
// RSSC HeadLine
// 2006-07-02 K.OHWADA
//=========================================================
// based on xoopsHeadline
// Id: index.php,v 1.3 2005/09/04 20:46:12 onokazu Exp
//=========================================================

include '../../mainfile.php';
include 'include/functions.php';
$hlman =& xoops_getmodulehandler('headline');;
$hlid = isset($_GET['id']) ? intval($_GET['id']) : 0;
$xoopsOption['template_main'] = 'rssc_headline_index.html';
include XOOPS_ROOT_PATH.'/header.php';
$headlines =& $hlman->getObjects(new Criteria('headline_display', 1));
$count = count($headlines);
for ($i = 0; $i < $count; $i++) {
	$xoopsTpl->append('feed_sites', array('id' => $headlines[$i]->getVar('headline_id'), 'name' => $headlines[$i]->getVar('headline_name')));
}
$xoopsTpl->assign('lang_headlines', _RSSC_HEADLINE_HEADLINES);
if ($hlid == 0) {
	if ( isset($headlines[0]) )
	{
		$hlid = $headlines[0]->getVar('headline_id');
	}
}
if ($hlid > 0) {
	$headline =& $hlman->get($hlid);
	if (is_object($headline)) {
		$renderer =& rssc_headline_getrenderer($headline);
		if (!$renderer->renderFeed()) {
			if ($xoopsConfig['debug_mode'] == 2) {
				$xoopsTpl->assign('headline', '<p>'.sprintf(_RSSC_HEADLINE_FAILGET, $headline->getVar('headline_name')).'<br />'.$renderer->getErrors().'</p>');
			}
		} else {
			$xoopsTpl->assign('headline', $renderer->getFeed());
		}
	}
}
include XOOPS_ROOT_PATH.'/footer.php';
?>