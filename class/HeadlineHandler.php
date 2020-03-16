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
 * Class HeadlineHandler
 * @package XoopsModules\Rssheadline
 */
class HeadlineHandler
{
    public $db;

    /**
     * HeadlineHandler constructor.
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        $this->db = $db;
    }

    /**
     * @param null $db
     * @return static
     */
    public static function getInstance($db = null)
    {
        static $instance;
        if (null === $instance) {
            $instance = new static($db);
        }

        return $instance;
    }

    /**
     * @return \XoopsModules\Rssheadline\Headline
     */
    public function &create()
    {
        $ret = new Headline();

        return $ret;
    }

    /**
     * @param $id
     * @return bool|\XoopsModules\Rssheadline\Headline
     */
    public function get($id)
    {
        $id = (int)$id;
        if ($id > 0) {
            $sql = 'SELECT * FROM ' . $this->db->prefix('rssheadline') . ' WHERE headline_id=' . $id;
            if (!$result = $this->db->query($sql)) {
                return false;
            }
            $numrows = $this->db->getRowsNum($result);
            if (1 == $numrows) {
                $headline = new Headline();
                $headline->assignVars($this->db->fetchArray($result));

                return $headline;
            }
        }

        return false;
    }

    /**
     * @param $headline
     * @return bool
     */
    public function insert($headline)
    {
        if ('rssheadline_headline' != mb_strtolower(get_class($headline))) {
            return false;
        }
        if (!$headline->cleanVars()) {
            return false;
        }
        foreach ($headline->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if (empty($headline_id)) {
            $headline_id = $this->db->genId('rssheadline_headline_id_seq');

            // add field headline_rssc_lid
            $sql = 'INSERT INTO '
                   . $this->db->prefix('rssheadline')
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
                   . $this->db->prefix('rssheadline')
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

    /**
     * @param $headline
     * @return bool
     */
    public function delete($headline)
    {
        if ('rssheadline_headline' != mb_strtolower(get_class($headline))) {
            return false;
        }
        $sql = sprintf('DELETE FROM %s WHERE headline_id = %u', $this->db->prefix('rssheadline'), $headline->getVar('headline_id'));
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param null $criteria
     * @return array
     */
    public function &getObjects($criteria = null)
    {
        $ret   = [];
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->db->prefix('rssheadline');
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
            $headline = new Headline();
            $headline->assignVars($myrow);
            $ret[] = &$headline;
            unset($headline);
        }

        return $ret;
    }

    /**
     * @param null $criteria
     * @return int
     */
    public function getCount($criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('rssheadline');
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
