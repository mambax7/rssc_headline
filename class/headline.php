<?php
// $Id: headline.php,v 1.2 2011/12/29 20:06:57 ohwada Exp $

// 2011-12-29 K.OHWADA
// PHP 5.3 : Assigning the return value of new by reference is now deprecated.

// 2006-09-01 K.OHWADA
// change create()

// 2006-07-02 K.OHWADA
// change Xoopsheadline to rssc_headline
// change xoopsheadline to rssc_headline
// add field headline_rssc_lid

//=========================================================
// RSSC HeadLine
// 2006-07-02 K.OHWADA
//=========================================================
// based on xoopsHeadline
// Id: headline.php,v 1.4 2005/08/03 12:40:01 onokazu Exp
//=========================================================

class rssc_headline_Headline extends XoopsObject
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

    public function cacheExpired()
    {
        if (time() - $this->getVar('headline_updated') > $this->getVar('headline_cachetime')) {
            return true;
        }

        return false;
    }
}

class rssc_headlineHeadlineHandler
{
    public $db;

    public function __construct(\XoopsDatabase $db)
    {
        $this->db = $db;
    }

    public static function getInstance($db = null)
    {
        static $instance;
        if (null === $instance) {
            $instance = new static($db);
        }

        return $instance;
    }

    public function &create()
    {
        $ret = new rssc_headline_Headline();

        return $ret;
    }

    public function get($id)
    {
        $id = (int)$id;
        if ($id > 0) {
            $sql = 'SELECT * FROM ' . $this->db->prefix('rssc_headline') . ' WHERE headline_id=' . $id;
            if (!$result = $this->db->query($sql)) {
                return false;
            }
            $numrows = $this->db->getRowsNum($result);
            if (1 == $numrows) {
                $headline = new rssc_headline_Headline();
                $headline->assignVars($this->db->fetchArray($result));

                return $headline;
            }
        }

        return false;
    }

    public function insert($headline)
    {
        if ('rssc_headline_headline' != mb_strtolower(get_class($headline))) {
            return false;
        }
        if (!$headline->cleanVars()) {
            return false;
        }
        foreach ($headline->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if (empty($headline_id)) {
            $headline_id = $this->db->genId('rssc_headline_headline_id_seq');

            // add field headline_rssc_lid
            $sql = 'INSERT INTO '
                   . $this->db->prefix('rssc_headline')
                   . ' (headline_id, headline_name, headline_url, headline_rssurl, headline_encoding, headline_cachetime, headline_asblock, headline_display, headline_weight, headline_mainimg, headline_mainfull, headline_mainmax, headline_blockimg, headline_blockmax, headline_xml, headline_updated, headline_rssc_lid) VALUES ('
                   . $headline_id
                   . ', '
                   . $this->db->quoteString($headline_name)
                   . ', '
                   . $this->db->quoteString($headline_url)
                   . ', '
                   . $this->db->quoteString($headline_rssurl)
                   . ', '
                   . $this->db->quoteString($headline_encoding)
                   . ', '
                   . $headline_cachetime
                   . ', '
                   . $headline_asblock
                   . ', '
                   . $headline_display
                   . ', '
                   . $headline_weight
                   . ', '
                   . $headline_mainimg
                   . ', '
                   . $headline_mainfull
                   . ', '
                   . $headline_mainmax
                   . ', '
                   . $headline_blockimg
                   . ', '
                   . $headline_blockmax
                   . ', '
                   . $this->db->quoteString($headline_xml)
                   . ', '
                   . time()
                   . ', '
                   . $headline_rssc_lid
                   . ')';
        } else {
            // add field headline_rssc_lid
            $sql = 'UPDATE '
                   . $this->db->prefix('rssc_headline')
                   . ' SET headline_name='
                   . $this->db->quoteString($headline_name)
                   . ', headline_url='
                   . $this->db->quoteString($headline_url)
                   . ', headline_rssurl='
                   . $this->db->quoteString($headline_rssurl)
                   . ', headline_encoding='
                   . $this->db->quoteString($headline_encoding)
                   . ', headline_cachetime='
                   . $headline_cachetime
                   . ', headline_asblock='
                   . $headline_asblock
                   . ', headline_display='
                   . $headline_display
                   . ', headline_weight='
                   . $headline_weight
                   . ', headline_mainimg='
                   . $headline_mainimg
                   . ', headline_mainfull='
                   . $headline_mainfull
                   . ', headline_mainmax='
                   . $headline_mainmax
                   . ', headline_blockimg='
                   . $headline_blockimg
                   . ', headline_blockmax='
                   . $headline_blockmax
                   . ', headline_xml = '
                   . $this->db->quoteString($headline_xml)
                   . ', headline_updated='
                   . $headline_updated
                   . ', headline_rssc_lid='
                   . $headline_rssc_lid
                   . ' WHERE headline_id='
                   . $headline_id;
        }
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }
        if (empty($headline_id)) {
            $headline_id = $this->db->getInsertId();
        }
        $headline->assignVar('headline_id', $headline_id);

        return $headline_id;
    }

    public function delete($headline)
    {
        if ('rssc_headline_headline' != mb_strtolower(get_class($headline))) {
            return false;
        }
        $sql = sprintf('DELETE FROM %s WHERE headline_id = %u', $this->db->prefix('rssc_headline'), $headline->getVar('headline_id'));
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }

    public function &getObjects($criteria = null)
    {
        $ret   = [];
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->db->prefix('rssc_headline');
        if (isset($criteria) && $criteria instanceof \CriteriaElement) {
            $sql   .= ' ' . $criteria->renderWhere();
            $sql   .= ' ORDER BY headline_weight ' . $criteria->getOrder();
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $headline = new rssc_headline_Headline();
            $headline->assignVars($myrow);
            $ret[] = &$headline;
            unset($headline);
        }

        return $ret;
    }

    public function getCount($criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('rssc_headline');
        if (isset($criteria) && $criteria instanceof \CriteriaElement) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return 0;
        }
        list($count) = $this->db->fetchRow($result);

        return $count;
    }
}
