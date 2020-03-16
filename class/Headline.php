<?php

namespace XoopsModules\Rssheadline;

// $Id: headline.php,v 1.2 2011/12/29 20:06:57 ohwada Exp $

// 2011-12-29 K.OHWADA
// PHP 5.3 : Assigning the return value of new by reference is now deprecated.

// 2006-09-01 K.OHWADA
// change create()

// 2006-07-02 K.OHWADA
// change Xoopsheadline to rssheadline
// change xoopsheadline to rssheadline
// add field headline_rssc_lid

//=========================================================
// RSSC HeadLine
// 2006-07-02 K.OHWADA
//=========================================================
// based on xoopsHeadline
// Id: headline.php,v 1.4 2005/08/03 12:40:01 onokazu Exp
//=========================================================

/**
 * Class Headline
 * @package XoopsModules\Rssheadline
 */
class Headline extends \XoopsObject
{
    public function __construct()
    {
        parent::__construct();
        $this->initVar('headline_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('headline_name', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('headline_url', XOBJ_DTYPE_TXTBOX, null, true, 255);

        // --- rssurl ---
        $this->initVar('headline_rssurl', XOBJ_DTYPE_TXTBOX, null, false, 255);
        // ---

        $this->initVar('headline_cachetime', XOBJ_DTYPE_INT, 600, false);
        $this->initVar('headline_asblock', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('headline_display', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('headline_encoding', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('headline_weight', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('headline_mainimg', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('headline_mainfull', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('headline_mainmax', XOBJ_DTYPE_INT, 10, false);
        $this->initVar('headline_blockimg', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('headline_blockmax', XOBJ_DTYPE_INT, 10, false);
        $this->initVar('headline_xml', XOBJ_DTYPE_SOURCE, null, false);
        $this->initVar('headline_updated', XOBJ_DTYPE_INT, 0, false);

        // --- add field headline_rssc_lid ---
        $this->initVar('headline_rssc_lid', XOBJ_DTYPE_INT, 0, false);
        // ---
    }

    /**
     * @return bool
     */
    public function cacheExpired()
    {
        if (time() - $this->getVar('headline_updated') > $this->getVar('headline_cachetime')) {
            return true;
        }

        return false;
    }
}
