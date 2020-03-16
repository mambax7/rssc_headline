<?php
// $Id: admin.php,v 1.1 2011/12/29 14:41:32 ohwada Exp $

// 2006-09-01 K.OHWADA
// use RSSC lang pack

// 2006-07-02 K.OHWADA

//=========================================================
// RSSC HeadLine
// 2006-07-02 K.OHWADA
//=========================================================
// based on xoopsHeadline
// Id: admin.php,v 1.2 2005/03/18 12:52:49 onokazu Exp
//=========================================================

//%%%%%%        Admin Module Name  Headlines         %%%%%
define('_AM_RSSHEADLINE_DBUPDATED', 'Database Updated Successfully!');
define('_AM_RSSHEADLINE_HEADLINES', 'Headlines Configuration');
define('_AM_RSSHEADLINE_HLMAIN', 'Headline Main');
define('_AM_RSSHEADLINE_SITENAME', 'Site Name');
define('_AM_RSSHEADLINE_URL', 'URL');
define('_AM_RSSHEADLINE_ORDER', 'Order');
define('_AM_RSSHEADLINE_ENCODING', 'RSS Encoding');
define('_AM_RSSHEADLINE_CACHETIME', 'Cache Time');
define('_AM_RSSHEADLINE_MAINSETT', 'Main Page Settings');
define('_AM_RSSHEADLINE_BLOCKSETT', 'Block Settings');
define('_AM_RSSHEADLINE_DISPLAY', 'Display in main page');
define('_AM_RSSHEADLINE_DISPIMG', 'Display image');
define('_AM_RSSHEADLINE_DISPFULL', 'Display in full view');
define('_AM_RSSHEADLINE_DISPMAX', 'Max items to display');
define('_AM_RSSHEADLINE_ASBLOCK', 'Display in block');
define('_AM_RSSHEADLINE_ADDHEADL', 'Add Headline');
define('_AM_RSSHEADLINE_URLEDFXML', 'URL of RDF/RSS file');
define('_AM_RSSHEADLINE_EDITHEADL', 'Edit Headline');
define('_AM_RSSHEADLINE_WANTDEL', 'Are you sure you want to delete headline for %s?');
define('_AM_RSSHEADLINE_INVALIDID', 'Invalid ID');
define('_AM_RSSHEADLINE_OBJECTNG', 'Object does not exist');
define('_AM_RSSHEADLINE_FAILUPDATE', 'Failed saving data to database for headline %s');
define('_AM_RSSHEADLINE_FAILDELETE', 'Failed deleting data from database for headline %s');

// 2006-07-02 K.OHWADA
define('_AM_RSSHEADLINE_INDEX_DESC', 'Discover <b>URL of RDF/RSS fileL</b> automatically and detect <b>RSS Encoding</b> automatically, <br>when you dont fill, <br>if web site support "RSS Auto Discovery"');
//define('_AM_RSSHEADLINE_RSSC_LINK_RSS_EXIST','Already exists this "RDF/RSS URL"');
//define('_AM_RSSHEADLINE_RSSC_LINK_RSS_EXIST_MORE','There are twe or more links which have same "RDF/RSS/ATOM URL"');
//define('_AM_RSSHEADLINE_RSSC_LINK_LID_EXIST_NOT','There are no link with this link');
//define('_AM_RSSHEADLINE_RSSC_AUTO_FAILD','RSS Auto Discovery Faild');
//define('_AM_RSSHEADLINE_RSSC_LID','Link ID of RSS Center');
//define('_AM_RSSHEADLINE_RSSC_LID_UPDATE','Update Link ID');
//define('_AM_RSSHEADLINE_RSSC_GOTO_LINK','Goto admin page of RSS center');

define('_AM_RSSHEADLINE_UPGRADEFAILED0', "Update failed - couldn't rename field '%s'");
define('_AM_RSSHEADLINE_UPGRADEFAILED1', "Update failed - couldn't add new fields");
define('_AM_RSSHEADLINE_UPGRADEFAILED2', "Update failed - couldn't rename table '%s'");
define('_AM_RSSHEADLINE_ERROR_COLUMN', 'Could not create column in database : %s');
define('_AM_RSSHEADLINE_ERROR_BAD_XOOPS', 'This module requires XOOPS %s+ (%s installed)');
define('_AM_RSSHEADLINE_ERROR_BAD_PHP', 'This module requires PHP version %s+ (%s installed)');
define('_AM_RSSHEADLINE_ERROR_TAG_REMOVAL', 'Could not remove tags from Tag Module');

define('_AM_RSSHEADLINE_FOLDERS_DELETED_OK', 'Upload Folders have been deleted');

// Error Msgs
define('_AM_RSSHEADLINE_ERROR_BAD_DEL_PATH', 'Could not delete %s directory');
define('_AM_RSSHEADLINE_ERROR_BAD_REMOVE', 'Could not delete %s');
define('_AM_RSSHEADLINE_ERROR_NO_PLUGIN', 'Could not load plugin');
