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

//require_once XOOPS_ROOT_PATH . '/modules/rssheadline/include/functions.php';

/**
 * @param $options
 * @return array
 */
function b_rssheadline_show($options)
{
    global $xoopsConfig;
    $block     = [];
    $hlman     = xoops_getModuleHandler('headline', 'rssheadline');
    $headlines = &$hlman->getObjects(new \Criteria('headline_asblock', 1));
    $count     = count($headlines);
    for ($i = 0; $i < $count; $i++) {
        $renderer = rssheadline_getrenderer($headlines[$i]);
        if (!$renderer->renderBlock()) {
            if (2 == $xoopsConfig['debug_mode']) {
                $block['feeds'][] = sprintf(_MD_RSSHEADLINE__FAILGET, $headlines[$i]->getVar('headline_name')) . '<br>' . $renderer->getErrors();
            }
            continue;
        }
        $block['feeds'][] = $renderer->getBlock();
    }

    return $block;
}
