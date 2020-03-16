<?php
// $Id: index.php,v 1.1 2011/12/29 14:41:31 ohwada Exp $

// 2006-07-02 K.OHWADA
// change _HL_xx to _MD_RSSHEADLINE__xx
// change xoopsheadline to rssheadline
// Fatal error: Call to a member function getVar() on a non-object

//=========================================================
// RSSC HeadLine
// 2006-07-02 K.OHWADA
//=========================================================
// based on xoopsHeadline
// Id: index.php,v 1.3 2005/09/04 20:46:12 onokazu Exp
//=========================================================

require dirname(dirname(__DIR__)) . '/mainfile.php';
//require __DIR__ . '/include/functions.php';
$hlman                                   = xoops_getModuleHandler('headline');
$hlid                                    = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$GLOBALS['xoopsOption']['template_main'] = 'rssheadline_index.html';
require XOOPS_ROOT_PATH . '/header.php';
$headlines = &$hlman->getObjects(new \Criteria('headline_display', 1));
$count     = count($headlines);
for ($i = 0; $i < $count; $i++) {
    $xoopsTpl->append('feed_sites', ['id' => $headlines[$i]->getVar('headline_id'), 'name' => $headlines[$i]->getVar('headline_name')]);
}
$xoopsTpl->assign('lang_headlines', _MD_RSSHEADLINE__HEADLINES);
if (0 == $hlid) {
    if (isset($headlines[0])) {
        $hlid = $headlines[0]->getVar('headline_id');
    }
}
if ($hlid > 0) {
    $headline = $hlman->get($hlid);
    if (is_object($headline)) {
        $renderer = rssheadline_getrenderer($headline);
        if (!$renderer->renderFeed()) {
            if (2 == $xoopsConfig['debug_mode']) {
                $xoopsTpl->assign('headline', '<p>' . sprintf(_MD_RSSHEADLINE__FAILGET, $headline->getVar('headline_name')) . '<br>' . $renderer->getErrors() . '</p>');
            }
        } else {
            $xoopsTpl->assign('headline', $renderer->getFeed());
        }
    }
}
require XOOPS_ROOT_PATH . '/footer.php';
