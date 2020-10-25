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

#  function AnnoncesNewCat
#####################################################
function AnnoncesNewCat($cat)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $classm, $ynprice, $myts;

    require XOOPS_ROOT_PATH . '/modules/myads/include/functions.php';

    require_once XOOPS_ROOT_PATH . '/modules/myads/class/arbre.php';

    $mytree = new XoopsArbre($xoopsDB->prefix('ann_categories'), 'cid', 'pid');

    // 文字化け対策 by Tom

    xoops_cp_header();

    OpenTable();

    ShowImg();

    echo '<form method="post" action="gest-cat.php" name="imcat"><input type="hidden" name="op" value="AnnoncesAddCat">
	    <b>' . _CLA_ADDSUBCAT . '</b></font><br><br>
		<TABLE BORDER=0>
    <TR>
      <TD>' . _CLA_CATNAME . ' </TD><TD colspan=2><input type="text" name="title" size="30" maxlength="100">&nbsp; ' . _CLA_IN . ' &nbsp;';

    $result = $xoopsDB->query('select pid, title, img, ordre from ' . $xoopsDB->prefix('ann_categories') . " where cid=$cat");

    [$pid, $title, $imgs, $ordre] = $xoopsDB->fetchRow($result);

    $mytree->makeMySelBox('title', 'title', $cat, 1);

    echo '</TD>
	</TR>
    <TR>
      <TD>' . _CLA_IMGCAT . '  </TD><TD colspan=2><SELECT NAME="img" onChange="showimage()">';

    $rep = XOOPS_ROOT_PATH . '/modules/myads/images/cat';

    $handle = opendir($rep);

    while ($file = readdir($handle)) {
        $filelist[] = $file;
    }

    asort($filelist);

    while (list($key, $file) = each($filelist)) {
        if (!preg_match('.gif|.jpg|.png', $file)) {
            if ('.' == $file || '..' == $file) {
                $a = 1;
            }
        } else {
            if ('default.gif' == $file) {
                echo "<option value=$file selected>$file</option>";
            } else {
                echo "<option value=$file>$file</option>";
            }
        }
    }

    echo '</select>&nbsp;&nbsp;<img src="' . XOOPS_URL . '/modules/myads/images/cat/default.gif" name="avatar" align="absmiddle"> </TD></TR><TR><TD>&nbsp;</TD><TD colspan=2>' . _CLA_REPIMGCAT . ' /modules/myads/images/cat/</TD></TR>';

    if (1 == $ynprice) {
        echo '<TR><TD>' . _CLA_DISPLPRICE2 . ' </TD><TD colspan=2><input type="radio" name="affprix" value="1">' . _CLA_OUI . '&nbsp;&nbsp; <input type="radio" name="affprix" value="0">' . _CLA_NON . ' (' . _CLA_INTHISCAT . ')</TD></TR>';
    }

    if ('ordre' == $classm) {
        echo '<TR><TD>' . _CLA_ORDRE . ' </TD><TD><input type="text" name="ordre" size="4"></TD><TD><input type="submit" value="' . _CLA_ADD . '"></TD></TR>';
    } else {
        echo '<TR><TD colspan=3><input type="submit" value="' . _CLA_ADD . '"></TD></TR>';
    }

    echo '</TABLE>
	    </form>';

    echo '<br>';

    CloseTable();

    xoops_cp_footer();
}

#  function AnnoncesModCat
#####################################################
function AnnoncesModCat($cat)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $classm, $ynprice, $myts;

    require XOOPS_ROOT_PATH . '/modules/myads/include/functions.php';

    require_once XOOPS_ROOT_PATH . '/modules/myads/class/arbre.php';

    $mytree = new XoopsArbre($xoopsDB->prefix('ann_categories'), 'cid', 'pid');

    xoops_cp_header();

    OpenTable();

    ShowImg();

    echo '<b>' . _CLA_MODIFCAT . '</b><br><br>';

    $result = $xoopsDB->query('select pid, title, img, ordre, affprix from ' . $xoopsDB->prefix('ann_categories') . " where cid=$cat");

    [$pid, $title, $imgs, $ordre, $affprix] = $xoopsDB->fetchRow($result);

    $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

    echo '<form action="gest-cat.php" method="post" name="imcat">
		<table border="0"><TR>
	<TD>' . _CLA_CATNAME . "   </TD><TD><input type=\"text\" name=\"title\" value=\"$title\" size=\"30\" maxlength=\"50\">&nbsp; " . _CLA_IN . ' &nbsp;';

    $mytree->makeMySelBox('title', 'title', $pid, 1);

    echo '</TD></TR><TR>
	<TD>' . _CLA_IMGCAT . '  </TD><TD><SELECT NAME="img" onChange="showimage()">';

    $rep = XOOPS_ROOT_PATH . '/modules/myads/images/cat';

    $handle = opendir($rep);

    while ($file = readdir($handle)) {
        $filelist[] = $file;
    }

    asort($filelist);

    while (list($key, $file) = each($filelist)) {
        if (!preg_match('.gif|.jpg|.png', $file)) {
            if ('.' == $file || '..' == $file) {
                $a = 1;
            }
        } else {
            if ($file == $imgs) {
                echo "<option value=$file selected>$file</option>";
            } else {
                echo "<option value=$file>$file</option>";
            }
        }
    }

    echo '</select>&nbsp;&nbsp;<img src="' . XOOPS_URL . "/modules/myads/images/cat/$imgs\" name=\"avatar\" align=\"absmiddle\"> </TD></TR><TR><TD>&nbsp;</TD><TD>" . _CLA_REPIMGCAT . ' /modules/myads/images/cat/</TD></TR>';

    if (1 == $ynprice) {
        echo '<TR><TD>' . _CLA_DISPLPRICE2 . ' </TD><TD colspan=2><input type="radio" name="affprix" value="1"';

        if ('1' == $affprix) {
            echo 'checked';
        }

        echo '>' . _CLA_OUI . '&nbsp;&nbsp; <input type="radio" name="affprix" value="0"';

        if ('0' == $affprix) {
            echo 'checked';
        }

        echo '>' . _CLA_NON . ' (' . _CLA_INTHISCAT . ')</TD></TR>';
    }

    if ('ordre' == $classm) {
        echo '<TR><TD>' . _CLA_ORDRE . " </TD><TD><input type=\"text\" name=\"ordre\" size=\"4\" value=\"$ordre\"></TD></TR>";
    } else {
        echo "<input type=\"hidden\" name=\"ordre\" value=\"$ordre\">";
    }

    echo '</table><P>';

    echo "<input type=\"hidden\" name=\"cidd\" value=\"$cat\">"
         . '<input type="hidden" name="op" value="AnnoncesModCatS">'
         . '<table border="0"><tr><td>'
         . '<input type="submit" value="'
         . _CLA_SAVMOD
         . '"></form></td><td>'
         . '<form action="gest-cat.php" method="post">'
         . "<input type=\"hidden\" name=\"cid\" value=\"$cat\">"
         . '<input type="hidden" name="op" value="AnoncesDelCat">'
         . '<input type="submit" value="'
         . _CLA_DEL
         . '"></form></td></tr></table>';

    CloseTable();

    xoops_cp_footer();
}

#  function AnnoncesModCatS
#####################################################
function AnnoncesModCatS($cidd, $cid, $img, $title, $ordre, $affprix)
{
    global $xoopsDB, $xoopsConfig, $myts;

    $title = $myts->addSlashes($title);

    $xoopsDB->query('update ' . $xoopsDB->prefix('ann_categories') . " set title='$title', pid='$cid', img='$img', ordre='$ordre', affprix='$affprix' where cid=$cidd");

    redirect_header('map.php', 1, _CLA_CATSMOD);

    exit();
}

#  function AnnoncesAddCat
#####################################################
function AnnoncesAddCat($title, $cid, $img, $ordre, $affprix)
{
    global $xoopsDB, $xoopsConfig, $myts;

    $title = $myts->addSlashes($title);

    if ('' == $title) {
        $title = '! ! ? ! !';
    }

    $xoopsDB->query('insert into ' . $xoopsDB->prefix('ann_categories') . " values (NULL, '$cid', '$title', '$img', '$ordre', '$affprix')");

    redirect_header('map.php', 1, _CLA_CATADD);

    exit();
}

#  function AnoncesDelCat
#####################################################
function AnoncesDelCat($cid, $ok = 0)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule;

    if (1 == (int)$ok) {
        $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

        $xoopsDB->queryF('delete from ' . $xoopsDB->prefix('ann_categories') . " where cid=$cid or pid=$cid");

        $xoopsDB->queryF('delete from ' . $xoopsDB->prefix('ann_annonces') . " where cid=$cid");

        redirect_header('map.php', 1, _CLA_CATDEL);

        exit();
    }

    xoops_cp_header();

    OpenTable();

    echo '<br><center><b>' . _CLA_SURDELCAT . '</b><br><br>';

    echo "[ <a href=\"gest-cat.php?op=AnoncesDelCat&cid=$cid&ok=1\">" . _CLA_OUI . '</a> | <a href="map.php">' . _CLA_NON . '</a> ]<br><br>';

    CloseTable();

    xoops_cp_footer();
}

#####################################################
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

$ok = $_GET['ok'] ?? '';

if (!isset($_POST['cid']) && isset($_GET['cid'])) {
    $cid = $_GET['cid'];
}
if (!isset($_POST['op']) && isset($_GET['op'])) {
    $op = $_GET['op'];
}

switch ($op) {
    case 'AnnoncesNewCat':
        AnnoncesNewCat($cid);
        break;
    case 'AnnoncesAddCat':
        AnnoncesAddCat($title, $cid, $img, $ordre, $affprix);
        break;
    case 'AnoncesDelCat':
        AnoncesDelCat($cid, $ok);
        break;
    case 'AnnoncesModCat':
        AnnoncesModCat($cid);
        break;
    case 'AnnoncesModCatS':
        AnnoncesModCatS($cidd, $cid, $img, $title, $ordre, $affprix);
        break;
    default:
        Index();
        break;
}
