<?php
// $Id: modinfo.php,v 1.1 2011/12/29 14:41:32 ohwada Exp $

// 2006-07-02 K.OHWADA
// change _MI_RSSHEADLINE_HEADLINES_xx to _MI_RSSHEADLINE_MD_RSSHEADLINE__xx

//=========================================================
// RSSC HeadLine
// 2006-07-02 K.OHWADA
//=========================================================
// based on xoopsHeadline
// Id: modinfo.php,v 1.2 2005/03/18 12:52:49 onokazu Exp
//=========================================================

// $Id: modinfo.php,v 1.1 2011/12/29 14:41:32 ohwada Exp $
// Module Info

// The name of this module
define('_MI_RSSHEADLINE_NAME', 'RSSC Headline');

// A brief description of this module
define('_MI_RSSHEADLINE_DESC', 'Displayes RSS/XML Newsfeed from other sites');

// Names of blocks for this module (Not all module has blocks)
define('_MI_RSSHEADLINE_BNAME', 'RSSC Headlines');

// Names of admin menu items
define('_MI_RSSHEADLINE_ADMENU1', 'RSSC List Headlines');

//Menu
define('_MI_RSSHEADLINE_MENU_HOME', 'Home');
define('_MI_RSSHEADLINE_MENU_01', 'Admin');
define('_MI_RSSHEADLINE_MENU_ABOUT', 'About');


//Config
define('MI_RSSHEADLINE_EDITOR_ADMIN', 'Editor: Admin');
define('MI_RSSHEADLINE_EDITOR_ADMIN_DESC', 'Select the Editor to use by the Admin');
define('MI_RSSHEADLINE_EDITOR_USER', 'Editor: User');
define('MI_RSSHEADLINE_EDITOR_USER_DESC', 'Select the Editor to use by the User');

//Help
define('_MI_RSSHEADLINE_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_RSSHEADLINE_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('_MI_RSSHEADLINE_BACK_2_ADMIN', 'Back to Administration of ');
define('_MI_RSSHEADLINE_OVERVIEW', 'Overview');

//define('_MI_RSSHEADLINE_HELP_DIR', __DIR__);

//help multi-page
define('_MI_RSSHEADLINE_DISCLAIMER', 'Disclaimer');
define('_MI_RSSHEADLINE_LICENSE', 'License');
define('_MI_RSSHEADLINE_SUPPORT', 'Support');
