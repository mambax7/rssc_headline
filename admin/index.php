<?php
// $Id: index.php,v 1.1 2011/12/29 14:41:32 ohwada Exp $

// 2006-09-10 K.OHWADA
// use RSSC_CODE_DISCOVER_FAILED etc

// 2006-07-02 K.OHWADA
// change xoopsheadline to rssc_headline

//=========================================================
// RSSC HeadLine
// 2006-07-02 K.OHWADA
//=========================================================
// based on xoopsHeadline
// Id: index.php,v 1.4 2005/08/03 12:40:01 onokazu Exp
//=========================================================

include '../../../include/cp_header.php';
include XOOPS_ROOT_PATH.'/modules/rssc_headline/include/functions.php';

// --- define rssc handler ---
$FLAG_ADD_ERROR = false;	// show to check exist link
$FLAG_DEL_RSSC  = false;	// delete RSSC link record

$ENCODINGS = array('auto' => 'AUTO', 'utf-8' => 'UTF-8', 'iso-8859-1' => 'ISO-8859-1', 'us-ascii' => 'US-ASCII');

$rssc_handler = & xoops_getmodulehandler('rssc', 'rssc_headline');
// ---

$op = 'list';

if (!empty($_GET['op']) && ($_GET['op'] == 'delete' || $_GET['op'] == 'edit')) {
    $op = $_GET['op'];
    $headline_id = intval($_GET['headline_id']);
} elseif (!empty($_POST['op'])) {
    $op = $_POST['op'];
}

if ($op == 'list') {
    include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
    $hlman =& xoops_getmodulehandler('headline');;
    $headlines =& $hlman->getObjects();
    $count = count($headlines);
    xoops_cp_header();
    echo "<h4>"._AM_HEADLINES."</h4>";
    echo '<form name="rssc_headline_form" action="index.php" method="post"><table><tr><td>'._AM_SITENAME.'</td><td>'._AM_CACHETIME.'</td><td>'._AM_ENCODING.'</td><td>'._AM_DISPLAY.'</td><td>'._AM_ASBLOCK.'</td><td>'._AM_ORDER.'</td><td>&nbsp;</td></tr>';
    for ($i = 0; $i < $count; $i++) {

// --- rssc_obj ---
		$headline_rssc_lid = $headlines[$i]->getVar('headline_rssc_lid');
		$rssc_handler->get( $headline_rssc_lid );
		$old_cachetime = $rssc_handler->get_cache_var( 'cachetime' );
		$encoding      = $rssc_handler->get_cache_var( 'encoding' );
// ---

        echo '<tr><td>'.$headlines[$i]->getVar('headline_name').'</td>
        <td><select name="headline_cachetime[]">';
        $cachetime = array('3600' => sprintf(_HOUR, 1), '18000' => sprintf(_HOURS, 5), '86400' => sprintf(_DAY, 1), '259200' => sprintf(_DAYS, 3), '604800' => sprintf(_WEEK, 1), '2592000' => sprintf(_MONTH, 1));
        foreach ($cachetime as $value => $name) {
           echo '<option value="'.$value.'"';

// --- cachetime ---
//			if ($value == $headlines[$i]->getVar('headline_cachetime')) {

			if ($value == $old_cachetime) 
// ---

            {
                echo ' selected="selecetd"';
            }
            echo '>'.$name.'</option>';
        }
        echo '</select></td>
        <td><select name="headline_encoding[]">';

// --- encoding list ---
//      $encodings = array('utf-8' => 'UTF-8', 'iso-8859-1' => 'ISO-8859-1', 'us-ascii' => 'US-ASCII');
// ---

// --- encoding list ---
//      foreach ($encodings as $value => $name)

		foreach ($ENCODINGS as $value => $name) 
// ---

        {
            echo '<option value="'.$value.'"';

// --- encoding ---
//          if ($value == $headlines[$i]->getVar('headline_encoding')) 

			if ($value == $encoding) 
// ---

            {
                echo ' selected="selecetd"';
            }
            echo '>'.$name.'</option>';
        }
        echo '</select></td>';
        echo '<td><input type="checkbox" value="1" name="headline_display['.$headlines[$i]->getVar('headline_id').']"';
        if (1 == $headlines[$i]->getVar('headline_display')) {
            echo ' checked="checked"';
        }
        echo ' /></td>';
        echo '<td><input type="checkbox" value="1" name="headline_asblock['.$headlines[$i]->getVar('headline_id').']"';
        if (1 == $headlines[$i]->getVar('headline_asblock')) {
            echo ' checked="checked"';
        }
        echo ' /></td>';
        echo '<td><input type="text" maxlength="3" size="4" name="headline_weight[]" value="'.$headlines[$i]->getVar('headline_weight').'" /><td><a href="index.php?op=edit&amp;headline_id='.$headlines[$i]->getVar('headline_id').'">'._EDIT.'</a>&nbsp;<a href="index.php?op=delete&amp;headline_id='.$headlines[$i]->getVar('headline_id').'">'._DELETE.'</a><input type="hidden" name="headline_id[]" value="'.$headlines[$i]->getVar('headline_id').'" /></td></tr>';
    }
    echo '</table><div style="text-align:center"><input type="hidden" name="op" value="update" /><input type="submit" name="headline_submit" value="'._SUBMIT.'" /></div></form>';
    
// --- description ---
	echo "<br /><br />\n";
	echo _AM_INDEX_DESC;
	echo "<br /><br />\n";
// ---
    
    $form = new XoopsThemeForm(_AM_ADDHEADL, 'rssc_headline_form_new', 'index.php');
    $form->addElement(new XoopsFormText(_AM_SITENAME, 'headline_name', 50, 255), true);
    $form->addElement(new XoopsFormText(_AM_URL, 'headline_url', 50, 255, 'http://'), true);

// --- rssurl ---
//  $form->addElement(new XoopsFormText(_AM_URLEDFXML, 'headline_rssurl', 50, 255, 'http://'), true);

	$form->addElement(new XoopsFormText(_AM_URLEDFXML, 'headline_rssurl', 50, 255, 'http://'), false);
// ---

    $form->addElement(new XoopsFormText(_AM_ORDER, 'headline_weight', 4, 3, 0));

// --- encoding list ---
//  $enc_sel = new XoopsFormSelect(_AM_ENCODING, 'headline_encoding', 'utf-8');
//  $enc_sel->addOptionArray(array('utf-8' => 'UTF-8', 'iso-8859-1' => 'ISO-8859-1', 'us-ascii' => 'US-ASCII'));

	$enc_sel = new XoopsFormSelect(_AM_ENCODING, 'headline_encoding', 'auto');
	$enc_sel->addOptionArray( $ENCODINGS );
// ---

    $form->addElement($enc_sel);
    $cache_sel = new XoopsFormSelect(_AM_CACHETIME, 'headline_cachetime', 86400);
    $cache_sel->addOptionArray(array('3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK, '2592000' => _MONTH));
    $form->addElement($cache_sel);

    $form->insertBreak(_AM_MAINSETT);
    $form->addElement(new XoopsFormRadioYN(_AM_DISPLAY, 'headline_display', 1, _YES, _NO));
    $form->addElement(new XoopsFormRadioYN(_AM_DISPIMG, 'headline_mainimg', 0, _YES, _NO));
    $form->addElement(new XoopsFormRadioYN(_AM_DISPFULL, 'headline_mainfull', 0, _YES, _NO));
    $mmax_sel = new XoopsFormSelect(_AM_DISPMAX, 'headline_mainmax', 10);
    $mmax_sel->addOptionArray(array('1' => 1, '5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30));
    $form->addElement($mmax_sel);

    $form->insertBreak(_AM_BLOCKSETT);
    $form->addElement(new XoopsFormRadioYN(_AM_ASBLOCK, 'headline_asblock', 1, _YES, _NO));
    $form->addElement(new XoopsFormRadioYN(_AM_DISPIMG, 'headline_blockimg', 0, _YES, _NO));
    $bmax_sel = new XoopsFormSelect(_AM_DISPMAX, 'headline_blockmax', 5);
    $bmax_sel->addOptionArray(array('1' => 1, '5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30));
    $form->addElement($bmax_sel);


    $form->insertBreak();
    $form->addElement(new XoopsFormHidden('op', 'addgo'));
    $form->addElement(new XoopsFormButton('', 'headline_submit2', _SUBMIT, 'submit'));
    $form->display();
    xoops_cp_footer();
    exit();
}

if ($op == 'update') {
    $hlman =& xoops_getmodulehandler('headline');
    $i = 0;
    $msg = '';
    foreach ($_POST['headline_id'] as $id) {
        $hl =& $hlman->get($id);
        if (!is_object($hl)) {
            $i++;
            continue;
        }

// --- rssc_obj ---
		$headline_rssc_lid = $hl->getVar('headline_rssc_lid');
		$rssc_handler->get( $headline_rssc_lid );
		$old_cachetime = $rssc_handler->get_cache_var( 'cachetime' );
		$encoding      = $rssc_handler->get_cache_var( 'encoding' );
// ---

        $headline_display[$id] = empty($_POST['headline_display'][$id]) ? 0 : $_POST['headline_display'][$id];
        $headline_asblock[$id] = empty($_POST['headline_asblock'][$id]) ? 0 : $_POST['headline_asblock'][$id];
//      $old_cachetime = $hl->getVar('headline_cachetime');
//      $hl->setVar('headline_cachetime', $_POST['headline_cachetime'][$i]);
        $old_display = $hl->getVar('headline_display');
        $hl->setVar('headline_display', $headline_display[$id]);
        $hl->setVar('headline_weight', $_POST['headline_weight'][$i]);
        $old_asblock = $hl->getVar('headline_asblock');
        $hl->setVar('headline_asblock', $headline_asblock[$id]);
//      $old_encoding = $hl->getVar('headline_encoding');
        if (!$hlman->insert($hl)) {
            $msg .= '<br />'.sprintf(_AM_FAILUPDATE, $hl->getVar('headline_name'));
        } else {

// --- update to rssc ---
		if ( !$rssc_handler->update_headline( $headline_rssc_lid, $i ) )
		{
			$msg .= '<br />'.sprintf(_AM_FAILUPDATE, $hl->getVar('headline_name'));
		}
// ---

// --- update cache ---
//            if ($hl->getVar('headline_xml') == '') {
//                $renderer =& rssc_headline_getrenderer($hl);
//                $renderer->updateCache();
//            }

			if ( $rssc_handler->get_feed_count($headline_rssc_lid) == 0 ) 
			{
				$rssc_handler->update_cache($headline_rssc_lid);
			}
// ---

        }
        $i++;
    }

    if ($msg != '') {
        xoops_cp_header();
        echo "<h4>"._AM_HEADLINES."</h4>";
        xoops_error($msg);
        xoops_cp_footer();
        exit();
    }

    redirect_header('index.php', 2, _AM_DBUPDATED);
}

if ($op == 'addgo') {

// --- check rssc ---
	$ret1 = $rssc_handler->check_add_headline();

// exist rss link
	if ( $FLAG_ADD_ERROR && ($ret1 == RSSC_CODE_LINK_ALREADY) )
	{
		xoops_cp_header();
		echo "<h4>"._AM_HEADLINES."</h4>";
		xoops_error( _RSSC_LINK_ALREADY );
		echo "<br />\n";
		echo $rssc_handler->getErrors('s');
		xoops_cp_footer();
		exit();
	}
// ---

    $hlman =& xoops_getmodulehandler('headline');
    $hl =& $hlman->create();
    $hl->setVar('headline_name', $_POST['headline_name']);
//    $hl->setVar('headline_url', $_POST['headline_url']);
//    $hl->setVar('headline_rssurl', $_POST['headline_rssurl']);
    $hl->setVar('headline_display', $_POST['headline_display']);
    $hl->setVar('headline_weight', $_POST['headline_weight']);
    $hl->setVar('headline_asblock', $_POST['headline_asblock']);
//    $hl->setVar('headline_encoding', $_POST['headline_encoding']);
//    $hl->setVar('headline_cachetime', $_POST['headline_cachetime']);
    $hl->setVar('headline_mainfull', $_POST['headline_mainfull']);
    $hl->setVar('headline_mainimg', $_POST['headline_mainimg']);
    $hl->setVar('headline_mainmax', $_POST['headline_mainmax']);
    $hl->setVar('headline_blockimg', $_POST['headline_blockimg']);
    $hl->setVar('headline_blockmax', $_POST['headline_blockmax']);

// --- headline_id ---
//  if (!$hlman->insert($hl))

	$headline_id = $hlman->insert($hl);
	if ( !$headline_id ) 
// ---

    {
        $msg = sprintf(_AM_FAILUPDATE, $hl->getVar('headline_name'));
        $msg .= '<br />'.$hl->getErrors();
        xoops_cp_header();
        echo "<h4>"._AM_HEADLINES."</h4>";
        xoops_error($msg);
        xoops_cp_footer();
        exit();
    }

// --- insert to rssc ---
	$new_rssc_lid = $rssc_handler->add_headline( $headline_id );
	if ( !$new_rssc_lid )
	{
		xoops_cp_header();
		echo "<h4>"._AM_HEADLINES."</h4>";
		xoops_error( _RSSC_DB_ERROR );
		echo $hl->getVar('headline_name');
		echo "<br />\n";
		echo $rssc_handler->getErrors('s');
		xoops_cp_footer();
		exit();
	}
// ---

// --- insert lid to headline ---
	$hl =& $hlman->get($headline_id);
	$hl->setVar('headline_rssc_lid', $new_rssc_lid);
	if ( !$hlman->insert($hl) )
	{
		$msg = sprintf( _AM_FAILUPDATE, $hl->getVar('headline_name') );
		$msg .= '<br />'.$hl->getErrors();
		xoops_cp_header();
		echo "<h4>"._AM_HEADLINES."</h4>";
		xoops_error($msg);
		xoops_cp_footer();
		exit();
	}
// ---

// --- update cache ---
//		if ($hl->getVar('headline_xml') == '')
//		{
//			$renderer =& rssc_headline_getrenderer($hl);
//			$renderer->updateCache();
//		}

		$ret2 = $rssc_handler->refresh_for_add_headline( $new_rssc_lid );
		if ( $ret2 == RSSC_CODE_DB_ERROR )
		{
			xoops_cp_header();
			echo "<h4>"._AM_HEADLINES."</h4>";
			xoops_error( _RSSC_DB_ERROR );
			echo $hl->getVar('headline_name');
			echo "<br />\n";
			echo $rssc_handler->getErrors('s');
			xoops_cp_footer();
			exit();
		}
// ---

// --- goto edit form ---
	$code = 0;

	if ( $ret1 == RSSC_CODE_DISCOVER_FAILED )
	{
		$code = $ret1;
	}
	elseif ( $ret2 == RSSC_CODE_PARSE_FAILED )
	{
		$code = $ret2;
	}
	elseif ( $ret2 == RSSC_CODE_REFRESH_ERROR )
	{
		$code = $ret2;
	}

	if ($code != 0)
	{
		$url = 'index.php?op=edit&amp;code='.$code.'&amp;headline_id='.$headline_id;
		redirect_header($url, 2, _AM_DBUPDATED);
		exit();
	}
// ---

// --- show parse result ---
	if ( $ret2 == RSSC_CODE_PARSE_MSG )
	{
		$msg  = _AM_DBUPDATED;
		$msg .= "<br /><br />";
		$msg .= $rssc_handler->get_parse_result();
		redirect_header('index.php', 5, $msg);
		exit();
	}
// ---

    redirect_header('index.php', 2, _AM_DBUPDATED);
}

if ($op == 'edit') {

    if ($headline_id <= 0) {
        xoops_cp_header();
        echo "<h4>"._AM_HEADLINES."</h4>";
        xoops_error(_AM_INVALIDID);
        xoops_cp_footer();
        exit();
    }
    $hlman =& xoops_getmodulehandler('headline');
    $hl =& $hlman->get($headline_id);
    if (!is_object($hl)) {
        xoops_cp_header();
        echo "<h4>"._AM_HEADLINES."</h4>";
        xoops_error(_AM_OBJECTNG);
        xoops_cp_footer();
        exit();
    }

    include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
    $form = new XoopsThemeForm(_AM_EDITHEADL, 'rssc_headline_form', 'index.php');

// --- headline_id ---
	$form->addElement(new XoopsFormLabel('Headline ID', $headline_id) );
// ---

// --- headline_rssc_lid ---
	$headline_rssc_lid = $hl->getVar('headline_rssc_lid');
	$rssc_url = XOOPS_URL.'/modules/'.RSSC_HEADLINE_RSSC_DIRNAME.'/admin/link_manage.php?op=mod_form&lid='.$headline_rssc_lid;
	$rssc_goto_url = '<a href="'.$rssc_url.'">'._RSSC_GOTO_RSSC_ADMIN_LINK.'</a>'."\n";

	$rssc_handler->get( $headline_rssc_lid );
	$url        = $rssc_handler->get_cache_var( 'url' );
	$rssurl    = $rssc_handler->get_cache_var( 'rssurl' );
	$encoding  = $rssc_handler->get_cache_var( 'encoding' );
	$cachetime = $rssc_handler->get_cache_var( 'cachetime' );

	$form->addElement(new XoopsFormText(_RSSC_RSSC_LID, 'headline_rssc_lid', 50, 255, $headline_rssc_lid), true);
	$form->addElement(new XoopsFormLabel('', $rssc_goto_url) );
	$form->addElement(new XoopsFormRadioYN(_RSSC_RSSC_LID_UPDATE, 'lid_update', 0, _YES, _NO));
// ---

    $form->addElement(new XoopsFormText(_AM_SITENAME, 'headline_name', 50, 255, $hl->getVar('headline_name')), true);

// --- url ---
//	$form->addElement(new XoopsFormText(_AM_URL, 'headline_url', 50, 255, $hl->getVar('headline_url')), true);

    $form->addElement(new XoopsFormText(_AM_URL, 'headline_url', 50, 255, $url), true);
// --- 

// --- rssurl ---
//  $form->addElement(new XoopsFormText(_AM_URLEDFXML, 'headline_rssurl', 50, 255, $hl->getVar('headline_rssurl')), true);

	$form->addElement(new XoopsFormText(_AM_URLEDFXML, 'headline_rssurl', 50, 255, $rssurl), false);
// ---

    $form->addElement(new XoopsFormText(_AM_ORDER, 'headline_weight', 4, 3, $hl->getVar('headline_weight')));

// --- encoding list ---
//    $enc_sel = new XoopsFormSelect(_AM_ENCODING, 'headline_encoding', $hl->getVar('headline_encoding'));
//    $enc_sel->addOptionArray(array('utf-8' => 'UTF-8', 'iso-8859-1' => 'ISO-8859-1', 'us-ascii' => 'US-ASCII'));

	$enc_sel = new XoopsFormSelect(_AM_ENCODING, 'headline_encoding', $encoding);
	$enc_sel->addOptionArray( $ENCODINGS );
//---

    $form->addElement($enc_sel);

// --- encoding list ---
//	$cache_sel = new XoopsFormSelect(_AM_CACHETIME, 'headline_cachetime', $hl->getVar('headline_cachetime'));

	$cache_sel = new XoopsFormSelect(_AM_CACHETIME, 'headline_cachetime', $cachetime);
//---

    $cache_sel->addOptionArray(array('3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK, '2592000' => _MONTH));
    $form->addElement($cache_sel);

    $form->insertBreak(_AM_MAINSETT);
    $form->addElement(new XoopsFormRadioYN(_AM_DISPLAY, 'headline_display', $hl->getVar('headline_display'), _YES, _NO));
    $form->addElement(new XoopsFormRadioYN(_AM_DISPIMG, 'headline_mainimg', $hl->getVar('headline_mainimg'), _YES, _NO));
    $form->addElement(new XoopsFormRadioYN(_AM_DISPFULL, 'headline_mainfull', $hl->getVar('headline_mainfull'), _YES, _NO));
    $mmax_sel = new XoopsFormSelect(_AM_DISPMAX, 'headline_mainmax', $hl->getVar('headline_mainmax'));
    $mmax_sel->addOptionArray(array('1' => 1, '5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30));
    $form->addElement($mmax_sel);

    $form->insertBreak(_AM_BLOCKSETT);
    $form->addElement(new XoopsFormRadioYN(_AM_ASBLOCK, 'headline_asblock', $hl->getVar('headline_asblock'), _YES, _NO));
    $form->addElement(new XoopsFormRadioYN(_AM_DISPIMG, 'headline_blockimg', $hl->getVar('headline_blockimg'), _YES, _NO));
    $bmax_sel = new XoopsFormSelect(_AM_DISPMAX, 'headline_blockmax', $hl->getVar('headline_blockmax'));
    $bmax_sel->addOptionArray(array('1' => 1, '5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30));
    $form->addElement($bmax_sel);
    $form->insertBreak();
    $form->addElement(new XoopsFormHidden('headline_id', $hl->getVar('headline_id')));
    $form->addElement(new XoopsFormHidden('op', 'editgo'));
    $form->addElement(new XoopsFormButton('', 'headline_submit', _SUBMIT, 'submit'));
    xoops_cp_header();
    echo "<h4>"._AM_HEADLINES."</h4><br />";

// --- warning message ---
	if ( isset($_GET['code']) )
	{
		$code = intval($_GET['code']);
	
		if ($code == RSSC_CODE_DISCOVER_FAILED)
		{
			xoops_error( _RSSC_DISCOVER_FAILED );
			echo "<br />\n";
		}
		elseif ($code == RSSC_CODE_PARSE_FAILED)
		{
			xoops_error( _RSSC_PARSE_FAILED );
			echo "<br />\n";
		}
		elseif ($code == RSSC_CODE_REFRESH_ERROR)
		{
			xoops_error( _RSSC_REFRESH_ERROR );
			echo "<br />\n";
		}
	}

	$ret = $rssc_handler->check_mod_form_headline($headline_rssc_lid, $url, $rssurl);
	if ($ret == RSSC_CODE_LINK_NOT_EXIST)
	{
		xoops_error( _RSSC_LINK_NOT_EXIST );
		echo "<br />\n";
	}
	elseif ($ret == RSSC_CODE_LINK_ALREADY)
	{
		xoops_error( _RSSC_LINK_EXIST_MORE );
		echo "<br />\n";
		echo $rssc_handler->getErrors('s');
		echo "<br />\n";
	}
// ---

    //echo '<a href="index.php">'. _AM_HLMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$hl->getVar('headline_name').'<br /><br />';
    $form->display();
    xoops_cp_footer();
    exit();
}

if ($op == 'editgo') {
    $headline_id = !empty($_POST['headline_id']) ? intval($_POST['headline_id']) : 0;
    if ($headline_id <= 0) {
        xoops_cp_header();
        echo "<h4>"._AM_HEADLINES."</h4>";
        xoops_error(_AM_INVALIDID);
        xoops_cp_footer();
        exit();
    }
    $hlman =& xoops_getmodulehandler('headline');;
    $hl =& $hlman->get($headline_id);
    if (!is_object($hl)) {
        xoops_cp_header();
        echo "<h4>"._AM_HEADLINES."</h4>";
        xoops_error(_AM_OBJECTNG);
        xoops_cp_footer();
        exit();
    }

// --- headline_rssc_lid ---
// update
	if ( isset($_POST['lid_update']) && $_POST['lid_update'] )
	{
		$headline_rssc_lid = $_POST['headline_rssc_lid'];
	}
// as the present situation
	else
	{
		$headline_rssc_lid = $hl->getVar('headline_rssc_lid');
	}
	$hl->setVar('headline_rssc_lid', $headline_rssc_lid);
// ---

    $hl->setVar('headline_name', $_POST['headline_name']);
//    $hl->setVar('headline_url', $_POST['headline_url']);
//    $hl->setVar('headline_encoding', $_POST['headline_encoding']);
//    $hl->setVar('headline_rssurl', $_POST['headline_rssurl']);
    $hl->setVar('headline_display', $_POST['headline_display']);
    $hl->setVar('headline_weight', $_POST['headline_weight']);
    $hl->setVar('headline_asblock', $_POST['headline_asblock']);
//    $hl->setVar('headline_cachetime', $_POST['headline_cachetime']);
    $hl->setVar('headline_mainfull', $_POST['headline_mainfull']);
    $hl->setVar('headline_mainimg', $_POST['headline_mainimg']);
    $hl->setVar('headline_mainmax', $_POST['headline_mainmax']);
    $hl->setVar('headline_blockimg', $_POST['headline_blockimg']);
    $hl->setVar('headline_blockmax', $_POST['headline_blockmax']);

    if (!$res = $hlman->insert($hl)) {
        $msg = sprintf(_AM_FAILUPDATE, $hl->getVar('headline_name'));
        $msg .= '<br />'.$hl->getHtmlErrors();
        xoops_cp_header();
        echo "<h4>"._AM_HEADLINES."</h4>";
        xoops_error($msg);
        xoops_cp_footer();
        exit();
    }

// --- update to rssc ---
	if ( !$rssc_handler->mod_headline( $headline_rssc_lid ) )
	{
		$msg = sprintf(_AM_FAILUPDATE, $hl->getVar('headline_name'));
		$msg .= '<br />'.$rssc_handler->getErrors('s');
		xoops_cp_header();
		echo "<h4>"._AM_HEADLINES."</h4>";
		xoops_error($msg);
		xoops_cp_footer();
		exit();
	}
// ---

// --- update cache ---
//        if ($hl->getVar('headline_xml') == '') {
//            $renderer =& rssc_headline_getrenderer($hl);
//            $renderer->updateCache();
//        }

	if ( $rssc_handler->get_feed_count( $headline_rssc_lid ) == 0 )
	{
		$rssc_handler->update_cache( $headline_rssc_lid );
	}
// ---

    redirect_header('index.php', 2, _AM_DBUPDATED);
}

if ($op == 'delete') {
    if ($headline_id <= 0) {
        xoops_cp_header();
        echo "<h4>"._AM_HEADLINES."</h4>";
        xoops_error(_AM_INVALIDID);
        xoops_cp_footer();
        exit();
    }
    $hlman =& xoops_getmodulehandler('headline');;
    $hl =& $hlman->get($headline_id);
    if (!is_object($hl)) {
        xoops_cp_header();
        echo "<h4>"._AM_HEADLINES."</h4>";
        xoops_error(_AM_OBJECTNG);
        xoops_cp_footer();
        exit();
    }
    xoops_cp_header();
    $name = $hl->getVar('headline_name');
    echo "<h4>"._AM_HEADLINES."</h4>";
    xoops_confirm(array('op' => 'deletego', 'headline_id' => $hl->getVar('headline_id')), 'index.php', sprintf(_AM_WANTDEL, $name));
    xoops_cp_footer();
    exit();
}

if ($op == 'deletego') {
    $headline_id = !empty($_POST['headline_id']) ? intval($_POST['headline_id']) : 0;
    if ($headline_id <= 0) {
        xoops_cp_header();
        echo "<h4>"._AM_HEADLINES."</h4>";
        xoops_error(_AM_INVALIDID);
        xoops_cp_footer();
        exit();
    }
    $hlman =& xoops_getmodulehandler('headline');;
    $hl =& $hlman->get($headline_id);
    if (!is_object($hl)) {
        xoops_cp_header();
        echo "<h4>"._AM_HEADLINES."</h4>";
        xoops_error(_AM_OBJECTNG);
        xoops_cp_footer();
        exit();
    }

// --- headline_rssc_lid ---
	$headline_rssc_lid = $hl->getVar('headline_rssc_lid');
// ---

    if (!$hlman->delete($hl)) {
        xoops_cp_header();
        echo "<h4>"._AM_HEADLINES."</h4>";
        xoops_error(sprintf(_AM_FAILDELETE, $hl->getVar('headline_name')));
        xoops_cp_footer();
        exit();
    }

// --- delete from rssc ---
	if ( $FLAG_DEL_RSSC && !$rssc_handler->del_headline( $headline_rssc_lid ) )
	{
		$msg = sprintf(_AM_FAILDELETE, $hl->getVar('headline_name'));
		$msg .= '<br />'.$rssc_handler->getErrors('s');
		xoops_cp_header();
		echo "<h4>"._AM_HEADLINES."</h4>";
		xoops_error($msg);
		xoops_cp_footer();
		exit();
	}
// ---

    redirect_header('index.php', 2, _AM_DBUPDATED);
}

?>