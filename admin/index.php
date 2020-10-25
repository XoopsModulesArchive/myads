<?php

//
// ------------------------------------------------------------------------- //
//               E-Xoops: Content Management for the Masses                  //
//                       < http://www.e-xoops.com >                          //
// ------------------------------------------------------------------------- //
// Original Author: Pascal Le Boustouller
// Author Website : pascal.e-xoops@perso-search.com
// Licence Type   : GPL
// ------------------------------------------------------------------------- //
include 'admin_header.php';
require XOOPS_ROOT_PATH . '/modules/myads/cache/config.php';

#  function Index
#####################################################
function Index()
{
    global $hlpfile, $xoopsDB, $xoopsConfig, $xoopsModule, $monnaie, $moderated, $ynprice, $classm, $myts;

    require XOOPS_ROOT_PATH . '/modules/myads/include/functions.php';

    require_once XOOPS_ROOT_PATH . '/modules/myads/class/arbre.php';

    $mytree = new XoopsArbre($xoopsDB->prefix('ann_categories'), 'cid', 'pid');

    xoops_cp_header();

    myads_admin_menu();

    $result = $xoopsDB->query('select lid, title, date from ' . $xoopsDB->prefix('ann_annonces') . " WHERE valid='No' order by lid");

    $numrows = $xoopsDB->getRowsNum($result);

    if ($numrows > 0) {
        OpenTable();

        echo '<B>' . _CLA_WAIT . '</B><br><br>';

        echo _CLA_THEREIS . " <b>$numrows</b> " . _CLA_WAIT . '<br><br>';

        echo '<TABLE WIDTH=100% CELLPADDING=2 CELLSPACING=0 BORDER=0>';

        $rank = 1;

        while (list($lid, $title, $date) = $xoopsDB->fetchRow($result)) {
            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

            $date2 = formatTimestamp($date, 's');

            if (is_int($rank / 2)) {
                $color = 'bg3';
            } else {
                $color = 'bg4';
            }

            echo "<TR class='$color'><TD><A HREF=\"index.php?op=IndexView&lid=$lid\">$title</A></TD><TD align=right> $date2</TD></TR>";

            $rank++;
        }

        echo '</TABLE>';

        CloseTable();

        echo '<br>';
    } else {
        OpenTable();

        echo _CLA_NOANNVAL;

        CloseTable();

        echo '<br>';
    }

    // Modify Annonces

    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('select COUNT(*) FROM ' . $xoopsDB->prefix('ann_annonces') . ''));

    if ($numrows > 0) {
        OpenTable();

        echo '<form method="post" action="index.php">'
             . '<b>'
             . _CLA_MODANN
             . '</b><br><br>'
             . ''
             . _CLA_NUMANN
             . ' <input type="text" name="lid" size="12" maxlength="11">&nbsp;&nbsp;'
             . '<input type="hidden" name="op" value="AnnoncesModAnnonce">'
             . '<input type="submit" value="'
             . _CLA_MODIF
             . '">'
             . '<br><br>'
             . _CLA_ALLMODANN
             . ''
             . '</form><center><A HREF="../index.php">'
             . _CLA_ACCESMYANN
             . '</A></center>';

        CloseTable();

        echo '<br>';
    }

    // Add Type

    OpenTable();

    echo '<form method="post" action="index.php">
		<b>' . _CLA_ADDTYPE . '</b><br><br>
		' . _CLA_TYPE . '	<input type="text" name="type" size="30" maxlength="100">	
		<input type="hidden" name="op" value="AnnoncesAddType">
		<input type="submit" value="' . _CLA_ADD . '">
		</form>';

    echo '<br>';

    // Modify Type

    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('select COUNT(*) FROM ' . $xoopsDB->prefix('ann_type') . ''));

    if ($numrows > 0) {
        echo '<form method="post" action="index.php">
			 <b>' . _CLA_MODTYPE . '</b></font><br><br>';

        $result = $xoopsDB->query('select id_type, nom_type from ' . $xoopsDB->prefix('ann_type') . ' order by nom_type');

        echo '' . _CLA_TYPE . ' <select name="id_type">';

        while (list($id_type, $nom_type) = $xoopsDB->fetchRow($result)) {
            $nom_type = htmlspecialchars($nom_type, ENT_QUOTES | ENT_HTML5);

            echo "<option value=\"$id_type\">$nom_type</option>";
        }

        echo '</select>
		    <input type="hidden" name="op" value="AnnoncesModType"> 
			<input type="submit" value="' . _CLA_MODIF . '">
		    </form>';

        CloseTable();

        echo '<br>';
    }

    // Add Prix

    OpenTable();

    echo '<form method="post" action="index.php">
		<b>' . _CLA_ADDPRIX . '</b><br><br>
		' . _CLA_TYPE . '	<input type="text" name="type" size="30" maxlength="100">	
		<input type="hidden" name="op" value="AnnoncesAddPrix">
		<input type="submit" value="' . _CLA_ADD . '">
		</form>';

    echo '<br>';

    // Modify Prix

    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('select COUNT(*) FROM ' . $xoopsDB->prefix('ann_prix') . ''));

    if ($numrows > 0) {
        echo '<form method="post" action="index.php">
			<b>' . _CLA_MODPRIX . '</b></font><br><br>';

        $result = $xoopsDB->query('select id_prix, nom_prix from ' . $xoopsDB->prefix('ann_prix') . ' order by nom_prix');

        echo 'Type : <select name="id_prix">';

        while (list($id_prix, $nom_prix) = $xoopsDB->fetchRow($result)) {
            $nom_prix = htmlspecialchars($nom_prix, ENT_QUOTES | ENT_HTML5);

            echo "<option value=\"$id_prix\">$nom_prix</option>";
        }

        echo '</select>
		    <input type="hidden" name="op" value="AnnoncesModPrix"> 
			<input type="submit" value="' . _CLA_MODIF . '">
		    </form>';

        CloseTable();

        echo '<br>';
    }

    xoops_cp_footer();
}

#  function IndexView
#####################################################
function IndexView($lid)
{
    //  global $xoopsDB, $xoopsConfig, $xoopsModule, $myts, $ynprice;

    global $xoopsDB, $xoopsModule, $xoopsConfig, $ynprice, $monnaie, $myts;

    require_once XOOPS_ROOT_PATH . '/modules/myads/class/arbre.php';

    $mytree = new XoopsArbre($xoopsDB->prefix('ann_categories'), 'cid', 'pid');

    xoops_cp_header();

    $result = $xoopsDB->query('select lid, cid, title, type, description, tel, price, typeprix, date, email, submitter, town, country, photo from ' . $xoopsDB->prefix('ann_annonces') . " WHERE valid='No' AND lid='$lid'");

    $numrows = $xoopsDB->getRowsNum($result);

    if ($numrows > 0) {
        OpenTable();

        echo '<B>' . _CLA_WAIT . '</B><br><br>';

        [$lid, $cid, $title, $type, $description, $tel, $price, $typeprix, $date, $email, $submitter, $town, $country, $photo] = $xoopsDB->fetchRow($result);

        $date2 = formatTimestamp($date, 's');

        $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

        $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

        $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5);

        $tel = htmlspecialchars($tel, ENT_QUOTES | ENT_HTML5);

        $price = htmlspecialchars($price, ENT_QUOTES | ENT_HTML5);

        $typeprix = htmlspecialchars($typeprix, ENT_QUOTES | ENT_HTML5);

        $submitter = htmlspecialchars($submitter, ENT_QUOTES | ENT_HTML5);

        $town = htmlspecialchars($town, ENT_QUOTES | ENT_HTML5);

        $country = htmlspecialchars($country, ENT_QUOTES | ENT_HTML5);

        echo '<form action="index.php" method="post">
			<TABLE><TR>
			<TD>' . _CLA_NUMANN . " </TD><TD>$lid / $date2</TD>
			</TR><TR>
			<TD>" . _CLA_SENDBY . " </TD><TD>$submitter</TD>
			</TR><TR>
			<TD>" . _CLA_EMAIL . " </TD><TD><input type=\"text\" name=\"email\" size=\"50\" value=\"$email\"></TD>
			</TR><TR>
			<TD>" . _CLA_TEL . " </TD><TD><input type=\"text\" name=\"tel\" size=\"50\" value=\"$tel\"></TD>
			</TR><TR>
			<TD>" . _CLA_TOWN . " </TD><TD><input type=\"text\" name=\"town\" size=\"50\" value=\"$town\"></TD>
			</TR><TR>
			<TD>" . _CLA_COUNTRY . " </TD><TD><input type=\"text\" name=\"country\" size=\"50\" value=\"$country\"></TD>
			</TR><TR>
			<TD>" . _CLA_TITLE2 . " </TD><TD><input type=\"text\" name=\"title\" size=\"50\" value=\"$title\"></TD>
			</TR><TR>
			<TD>" . _CLA_TYPE . ' </TD><TD><select name="type">';

        $result5 = $xoopsDB->query('select nom_type from ' . $xoopsDB->prefix('ann_type') . ' order by nom_type');

        while (list($nom_type) = $xoopsDB->fetchRow($result5)) {
            $sel = '';

            if ($nom_type == $type) {
                $sel = 'selected';
            }

            echo "<option value=\"$nom_type\" $sel>$nom_type</option>";
        }

        echo '</select></TD>
			</TR><TR>
			<TD>' . _CLA_PHOTO2 . " </TD><TD><input type=\"text\" name=\"photo\" size=\"50\" value=\"$photo\"></TD>
			</TR><TR>
			<TD>" . _CLA_ANNONCE . " </TD><TD><textarea name=\"description\" cols=\"60\" rows=\"10\">$description</textarea></TD>
			</TR><TR>";

        if (1 == $ynprice) {
            //			echo "<TD>"._CLA_PRICE2." </TD><TD><input type=\"text\" name=\"price\" size=\"10\" value=\"$price\"> $monnaie";

            echo '<TD>' . _CLA_PRICE2 . " </TD><TD><input type=\"text\" name=\"price\" size=\"20\" value=\"$price\"> $monnaie";

            $result3 = $xoopsDB->query('select nom_prix from ' . $xoopsDB->prefix('ann_prix') . ' order by id_prix');

            echo " <select name=\"typeprix\"><option value=\"$typeprix\">$typeprix</option>";

            while (list($nom_prix) = $xoopsDB->fetchRow($result3)) {
                echo "<option value=\"$nom_prix\">$nom_prix</option>";
            }

            echo '</select></TD>';
        }

        echo '</TR><TR>
			<TD>' . _CLA_CAT . ' </TD><TD>';

        $mytree->makeMySelBox('title', 'title', $cid);

        echo '</TD>
			</TR><TR>
			<TD>&nbsp;</TD><TD><SELECT NAME="op">
			<OPTION VALUE="AnnoncesValid"> ' . _CLA_OK . '
			<OPTION VALUE="AnnoncesDel"> ' . _CLA_DEL . '
			</SELECT><input type="submit" value="' . _CLA_GO . '"></TD>
			</TR></TABLE>';

        echo '<input type="hidden" name="valid" value="Yes">';

        echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">";

        echo "<input type=\"hidden\" name=\"date\" value=\"$date\">";

        echo "<input type=\"hidden\" name=\"submitter\" value=\"$submitter\">
			</form>";

        CloseTable();

        echo '<br>';
    }

    xoops_cp_footer();
}

#  function AnnoncesModAnnonce
#####################################################
function AnnoncesModAnnonce($lid)
{
    // for XOOPS CODE by Tom

    //global $xoopsDB, $xoopsModule, $xoopsConfig, $ynprice, $monnaie, $myts;

    global $xoopsDB, $xoopsModule, $xoopsConfig, $ynprice, $monnaie, $myts, $description;

    require_once XOOPS_ROOT_PATH . '/modules/myads/class/arbre.php';

    $mytree = new XoopsArbre($xoopsDB->prefix('ann_categories'), 'cid', 'pid');

    // for XOOPS CODE  by Tom

    require XOOPS_ROOT_PATH . '/include/xoopscodes.php';

    xoops_cp_header();

    $result = $xoopsDB->query('select lid, cid, title, type, description, tel, price, typeprix, date, email, submitter, town, country, valid, photo from ' . $xoopsDB->prefix('ann_annonces') . " where lid=$lid");

    OpenTable();

    echo '<b>' . _CLA_MODANN . '</b><br><br>';

    while (list($lid, $cid, $title, $type, $description, $tel, $price, $typeprix, $date, $email, $submitter, $town, $country, $valid, $photo) = $xoopsDB->fetchRow($result)) {
        $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

        $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

        $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5);

        $tel = htmlspecialchars($tel, ENT_QUOTES | ENT_HTML5);

        $price = htmlspecialchars($price, ENT_QUOTES | ENT_HTML5);

        $typeprix = htmlspecialchars($typeprix, ENT_QUOTES | ENT_HTML5);

        $submitter = htmlspecialchars($submitter, ENT_QUOTES | ENT_HTML5);

        $town = htmlspecialchars($town, ENT_QUOTES | ENT_HTML5);

        $country = htmlspecialchars($country, ENT_QUOTES | ENT_HTML5);

        $date2 = formatTimestamp($date, 's');

        echo '<form action="index.php" method=post>
		    <TABLE border=0><TR>
			<TD>' . _CLA_NUMANN . " </TD><TD>$lid / $date2</TD>
			</TR><TR>
			<TD>" . _CLA_SENDBY . " </TD><TD>$submitter</TD>
			</TR><TR>
			<TD>" . _CLA_EMAIL . " </TD><TD><input type=\"text\" name=\"email\" size=\"50\" value=\"$email\"></TD>
			</TR><TR>
			<TD>" . _CLA_TEL . " </TD><TD><input type=\"text\" name=\"tel\" size=\"50\" value=\"$tel\"></TD>
			</TR><TR>
			<TD>" . _CLA_TOWN . " </TD><TD><input type=\"text\" name=\"town\" size=\"50\" value=\"$town\"></TD>
			</TR><TR>
			<TD>" . _CLA_COUNTRY . " </TD><TD><input type=\"text\" name=\"country\" size=\"50\" value=\"$country\"></TD>
			</TR><TR>
			<TD>" . _CLA_TITLE2 . " </TD><TD><input type=\"text\" name=\"title\" size=\"50\" value=\"$title\"></TD>
			</TR><TR>
			<TD>" . _CLA_TYPE . ' </TD><TD><select name="type">';

        $result5 = $xoopsDB->query('select nom_type from ' . $xoopsDB->prefix('ann_type') . ' order by nom_type');

        while (list($nom_type) = $xoopsDB->fetchRow($result5)) {
            $sel = '';

            if ($nom_type == $type) {
                $sel = 'selected';
            }

            echo "<option value=\"$nom_type\" $sel>$nom_type</option>";
        }

        echo '</select></TD>
			</TR><TR>
			<TD>' . _CLA_CAT2 . ' </TD><TD>';

        $mytree->makeMySelBox('title', 'title', $cid);

        echo '</TD>
			</TR><TR>
			<TD>' . _CLA_ANNONCE . ' </TD><TD>';

        // add XOOPS CODE by Tom (hidden)

        //echo "<textarea name=\"description\" cols=\"60\" rows=\"10\">$description</textarea>";

        xoopsCodeTarea('description');

        xoopsSmilies('description');

        echo '</TD></TR><TR>
			<TD>' . _CLA_PHOTO2 . " </TD><TD><input type=\"text\" name=\"photo\" size=\"50\" value=\"$photo\"></TD>
			</TR><TR>";

        if (1 == $ynprice) {
            echo '<TD>' . _CLA_PRICE2 . " </TD><TD><input type=\"text\" name=\"price\" size=\"20\" value=\"$price\"> $monnaie";

            $result = $xoopsDB->query('select nom_prix from ' . $xoopsDB->prefix('ann_prix') . ' order by nom_prix');

            echo " <select name=\"id_prix\"><option value=\"$typeprix\">$typeprix</option>";

            while (list($nom_prix) = $xoopsDB->fetchRow($result)) {
                $nom_prix = htmlspecialchars($nom_prix, ENT_QUOTES | ENT_HTML5);

                echo "<option value=\"$nom_prix\">$nom_prix</option>";
            }

            echo '</select></TD>';
        }

        $time = time();

        echo '</TR><TR>
			<TD>&nbsp;</TD><TD><SELECT NAME="op">
			<OPTION VALUE="AnnoncesModAnnonceS"> ' . _CLA_MODIF . '
			<OPTION VALUE="AnnoncesDel"> ' . _CLA_DEL . '
			</SELECT><input type="submit" value="' . _CLA_GO . '"></TD>
			</TR></TABLE>';

        echo '<input type="hidden" name="valid" value="Yes">';

        echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">";

        echo "<input type=\"hidden\" name=\"date\" value=\"$time\">";

        echo "<input type=\"hidden\" name=\"submitter\" value=\"$submitter\">
		</form><br>";

        CloseTable();

        xoops_cp_footer();
    }
}

#  function AnnoncesModAnnonceS
#####################################################
function AnnoncesModAnnonceS($lid, $cat, $title, $type, $description, $tel, $price, $typeprix, $date, $email, $submitter, $town, $country, $valid, $photo)
{
    global $xoopsDB, $xoopsConfig, $myts;

    $title = $myts->addSlashes($title);

    $type = $myts->addSlashes($type);

    $description = $myts->addSlashes($description);

    $tel = $myts->addSlashes($tel);

    $price = $myts->addSlashes($price);

    $typeprix = $myts->addSlashes($typeprix);

    $submitter = $myts->addSlashes($submitter);

    $town = $myts->addSlashes($town);

    $country = $myts->addSlashes($country);

    $xoopsDB->query(
        'update '
        . $xoopsDB->prefix('ann_annonces')
        . " set cid='$cat', title='$title', type='$type', description='$description', tel='$tel', price='$price', typeprix='$typeprix', date='$date', email='$email', submitter='$submitter', town='$town', country='$country', valid='$valid', photo='$photo' where lid=$lid"
    );

    redirect_header('index.php', 1, _CLA_ANNMOD);

    exit();
}

#  function AnnoncesDel
#####################################################
function AnnoncesDel($lid, $photo)
{
    global $xoopsDB;

    $xoopsDB->query('delete from ' . $xoopsDB->prefix('ann_annonces') . " where lid=$lid");

    $destination = XOOPS_ROOT_PATH . '/modules/myads/images_ann';

    if ($photo) {
        if (file_exists("$destination/$photo")) {
            unlink("$destination/$photo");
        }
    }

    redirect_header('index.php', 1, _CLA_ANNDEL);

    exit();
}

#  function AnnoncesValid
#####################################################
function AnnoncesValid($lid, $cat, $title, $type, $description, $tel, $price, $typeprix, $date, $email, $submitter, $town, $country, $valid, $photo)
{
    global $xoopsDB, $xoopsConfig, $myts, $meta;

    $title = $myts->addSlashes($title);

    $type = $myts->addSlashes($type);

    $description = $myts->addSlashes($description);

    $tel = $myts->addSlashes($tel);

    $price = $myts->addSlashes($price);

    $typeprix = $myts->addSlashes($typeprix);

    $submitter = $myts->addSlashes($submitter);

    $town = $myts->addSlashes($town);

    $country = $myts->addSlashes($country);

    $xoopsDB->query(
        'update '
        . $xoopsDB->prefix('ann_annonces')
        . " set cid='$cat', title='$title', type='$type', description='$description', tel='$tel', price='$price', typeprix='$typeprix', date='$date', email='$email', submitter='$submitter', town='$town', country='$country', valid='$valid', photo='$photo'  where lid=$lid"
    );

    //	Specification for Japan:

    //	$message = ""._CLA_HELLO." $submitter,\n\n "._CLA_ANNACCEPT." :\n\n$type $title\n $description\n\n\n "._CLA_CONSULTTO."\n ".XOOPS_URL."/modules/myads/index.php?pa=viewannonces&lid=$lid\n\n "._CLA_THANK."\n\n"._CLA_TEAMOF." ".$meta['title']."\n".XOOPS_URL."";

    if ('' == $email) {
    } else {
        $message = "$submitter " . _CLA_HELLO . "\n\n " . _CLA_ANNACCEPT . " :\n\n$type $title\n $description\n\n\n " . _CLA_CONSULTTO . "\n " . XOOPS_URL . "/modules/myads/index.php?pa=viewannonces&lid=$lid\n\n " . _CLA_THANK . "\n\n" . _CLA_TEAMOF . ' ' . $meta['title'] . "\n" . XOOPS_URL . '';

        $subject = '' . _CLA_ANNACCEPT . '';

        $mail = getMailer();

        $mail->useMail();

        $mail->setFromName($meta['title']);

        $mail->setFromEmail($xoopsConfig['adminmail']);

        $mail->setToEmails($email);

        $mail->setSubject($subject);

        $mail->setBody($message);

        $mail->send();

        echo $mail->getErrors();
    }

    redirect_header('index.php', 1, _CLA_ANNVALID);

    exit();
}

#  function AnnoncesAddType
#####################################################
function AnnoncesAddType($type)
{
    global $xoopsDB, $xoopsConfig, $myts;

    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('select  COUNT(*)  FROM ' . $xoopsDB->prefix('ann_type') . " where nom_type='$type'"));

    if ($numrows > 0) {
        xoops_cp_header();

        OpenTable();

        echo '<br><center><b>' . _CLA_ERRORTYPE . " $nom_type " . _CLA_EXIST . '</b><br><br>';

        echo '<form method="post" action="index.php">
			<b>' . _CLA_ADDTYPE . '</b><br><br>
			' . _CLA_TYPE . '	<input type="text" name="type" size="30" maxlength="100">	
			<input type="hidden" name="op" value="AnnoncesAddType">
			<input type="submit" value="' . _CLA_ADD . '">
			</form>';

        CloseTable();

        xoops_cp_footer();
    } else {
        $type = $myts->addSlashes($type);

        if ('' == $type) {
            $type = '! ! ? ! !';
        }

        $xoopsDB->query('insert into ' . $xoopsDB->prefix('ann_type') . " values (NULL, '$type')");

        redirect_header('index.php', 1, _CLA_ADDTYPE2);

        exit();
    }
}

#  function AnnoncesModType
#####################################################
function AnnoncesModType($id_type)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $myts;

    xoops_cp_header();

    OpenTable();

    echo '<b>' . _CLA_MODTYPE . '</b><br><br>';

    $result = $xoopsDB->query('select id_type, nom_type from ' . $xoopsDB->prefix('ann_type') . " where id_type=$id_type");

    [$id_type, $nom_type] = $xoopsDB->fetchRow($result);

    $nom_type = htmlspecialchars($nom_type, ENT_QUOTES | ENT_HTML5);

    echo '<form action="index.php" method="post">'
         . ''
         . _CLA_TYPE
         . " <input type=\"text\" name=\"nom_type\" value=\"$nom_type\" size=\"51\" maxlength=\"50\"><br>"
         . "<input type=\"hidden\" name=\"id_type\" value=\"$id_type\">"
         . '<input type="hidden" name="op" value="AnnoncesModTypeS">'
         . '<table border="0"><tr><td>'
         . '<input type="submit" value="'
         . _CLA_SAVMOD
         . '"></form></td><td>'
         . '<form action="index.php" method="post">'
         . "<input type=\"hidden\" name=\"id_type\" value=\"$id_type\">"
         . '<input type="hidden" name="op" value="AnnoncesDelType">'
         . '<input type="submit" value="'
         . _CLA_DEL
         . '"></form></td></tr></table>';

    CloseTable();

    xoops_cp_footer();
}

#  function AnnoncesModTypeS
#####################################################
function AnnoncesModTypeS($id_type, $nom_type)
{
    global $xoopsDB, $xoopsConfig, $myts;

    $nom_type = $myts->addSlashes($nom_type);

    $xoopsDB->query('update ' . $xoopsDB->prefix('ann_type') . " set nom_type='$nom_type' where id_type='$id_type'");

    redirect_header('index.php', 1, _CLA_TYPEMOD);

    exit();
}

#  function AnnoncesDelType
#####################################################
function AnnoncesDelType($id_type)
{
    global $xoopsDB;

    $xoopsDB->query('delete from ' . $xoopsDB->prefix('ann_type') . " where id_type='$id_type'");

    redirect_header('index.php', 1, _CLA_TYPEDEL);

    exit();
}

#  function AnnoncesAddPrix
#####################################################
function AnnoncesAddPrix($type)
{
    global $xoopsDB, $xoopsConfig, $myts;

    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('select  COUNT(*)  FROM ' . $xoopsDB->prefix('ann_prix') . " where nom_prix='$type'"));

    if ($numrows > 0) {
        xoops_cp_header();

        OpenTable();

        echo '<br><center><b>' . _CLA_ERRORPRIX . " $nom_prix " . _CLA_EXIST . '</b><br><br>';

        echo '<form method="post" action="index.php">
			<b>' . _CLA_ADDPRIX . '</b><br><br>
			' . _CLA_TYPE . '	<input type="text" name="type" size="30" maxlength="100">	
			<input type="hidden" name="op" value="AnnoncesAddPrix">
			<input type="submit" value="' . _CLA_ADD . '">
			</form>';

        CloseTable();

        xoops_cp_footer();
    } else {
        $type = $myts->addSlashes($type);

        if ('' == $type) {
            $type = '! ! ? ! !';
        }

        $xoopsDB->query('insert into ' . $xoopsDB->prefix('ann_prix') . " values (NULL, '$type')");

        redirect_header('index.php', 1, _CLA_ADDPRIX2);

        exit();
    }
}

#  function AnnoncesModPrix
#####################################################
//function AnnoncesModPrix($id_prix, $nom_type)
function AnnoncesModPrix($id_prix)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $myts;

    xoops_cp_header();

    OpenTable();

    echo '<b>' . _CLA_MODPRIX . '</b><br><br>';

    $result = $xoopsDB->query('select nom_prix from ' . $xoopsDB->prefix('ann_prix') . " where id_prix=$id_prix");

    [$nom_prix] = $xoopsDB->fetchRow($result);

    $nom_prix = htmlspecialchars($nom_prix, ENT_QUOTES | ENT_HTML5);

    echo '<form action="index.php" method="post">'
         . ''
         . _CLA_TYPE
         . " <input type=\"text\" name=\"nom_prix\" value=\"$nom_prix\" size=\"51\" maxlength=\"50\"><br>"
         . "<input type=\"hidden\" name=\"id_prix\" value=\"$id_prix\">"
         . '<input type="hidden" name="op" value="AnnoncesModPrixS">'
         . '<table border="0"><tr><td>'
         . '<input type="submit" value="'
         . _CLA_SAVMOD
         . '"></form></td><td>'
         . '<form action="index.php" method="post">'
         . "<input type=\"hidden\" name=\"id_prix\" value=\"$id_prix\">"
         . '<input type="hidden" name="op" value="AnnoncesDelPrix">'
         . '<input type="submit" value="'
         . _CLA_DEL
         . '"></form></td></tr></table>';

    CloseTable();

    xoops_cp_footer();
}

#  function AnnoncesModPrixS
#####################################################
function AnnoncesModPrixS($id_prix, $nom_prix)
{
    global $xoopsDB, $xoopsConfig, $myts;

    $nom_prix = $myts->addSlashes($nom_prix);

    $xoopsDB->query('update ' . $xoopsDB->prefix('ann_prix') . " set nom_prix='$nom_prix' where id_prix='$id_prix'");

    redirect_header('index.php', 1, _CLA_PRIXMOD);

    exit();
}

#  function AnnoncesDelPrix
#####################################################
function AnnoncesDelPrix($id_prix)
{
    global $xoopsDB;

    $xoopsDB->query('delete from ' . $xoopsDB->prefix('ann_prix') . " where id_prix='$id_prix'");

    redirect_header('index.php', 1, _CLA_PRIXDEL);

    exit();
}

#####################################################
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

$pa = $_GET['pa'] ?? '';

if (!isset($_POST['lid']) && isset($_GET['lid'])) {
    $lid = $_GET['lid'];
}
if (!isset($_POST['op']) && isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (!isset($op)) {
    $op = '';
}

switch ($op) {
    case 'IndexView':
        IndexView($lid);
        break;
    case 'AnnoncesDelPrix':
        AnnoncesDelPrix($id_prix);
        break;
    case 'AnnoncesModPrix':
        AnnoncesModPrix($id_prix);
        break;
    case 'AnnoncesModPrixS':
        AnnoncesModPrixS($id_prix, $nom_prix);
        break;
    case 'AnnoncesAddPrix':
        AnnoncesAddPrix($type);
        break;
    case 'AnnoncesDelType':
        AnnoncesDelType($id_type);
        break;
    case 'AnnoncesModType':
        AnnoncesModType($id_type);
        break;
    case 'AnnoncesModTypeS':
        AnnoncesModTypeS($id_type, $nom_type);
        break;
    case 'AnnoncesAddType':
        AnnoncesAddType($type);
        break;
    case 'AnnoncesDel':
        AnnoncesDel($lid, $photo);
        break;
    case 'AnnoncesValid':
        AnnoncesValid($lid, $cid, $title, $type, $description, $tel, $price, $typeprix, $date, $email, $submitter, $town, $country, $valid, $photo);
        break;
    case 'AnnoncesModAnnonce':
        AnnoncesModAnnonce($lid);
        break;
    case 'AnnoncesModAnnonceS':
        AnnoncesModAnnonceS($lid, $cid, $title, $type, $description, $tel, $price, $id_prix, $date, $email, $submitter, $town, $country, $valid, $photo);
        break;
    default:
        Index();
        break;
}
