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
include 'header.php';
require XOOPS_ROOT_PATH . '/modules/myads/cache/config.php';

function AnnoncesDel($lid, $ok)
{
    global $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsTheme, $xoopsLogger;

    $result = $xoopsDB->query('select usid, photo FROM ' . $xoopsDB->prefix('ann_annonces') . " where lid=$lid");

    [$usid, $photo] = $xoopsDB->fetchRow($result);

    if ($xoopsUser) {
        $calusern = $xoopsUser->getVar('uid', 'E');

        if ($usid == $calusern) {
            if (1 == $ok) {
                $xoopsDB->queryF('delete from ' . $xoopsDB->prefix('ann_annonces') . " where lid=$lid");

                if ($photo) {
                    $destination = XOOPS_ROOT_PATH . '/modules/myads/images_ann';

                    if (file_exists("$destination/$photo")) {
                        unlink("$destination/$photo");
                    }
                }

                redirect_header('index.php', 1, _CLA_ANNDEL);

                exit();
            }

            OpenTable();

            echo '<br><center>';

            echo '<b>' . _CLA_SURDELANN . '</b><br><br>';

            echo "[ <a href=\"supprann.php?op=AnnoncesDel&lid=$lid&ok=1\">" . _CLA_OUI . '</a> | <a href="index.php">' . _CLA_NON . '</a> ]<br><br>';

            CloseTable();
        }
    }
}

function ModAnnonce($lid)
{
    // for XOOPS CODE by Tom

    //global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsUser, $monnaie, $moderated, $photomax, $ynprice, $xoopsTheme, $myts, $xoopsLogger;

    global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsUser, $monnaie, $moderated, $photomax, $ynprice, $xoopsTheme, $myts, $xoopsLogger, $description;

    // for XOOPS CODE  by Tom

    require XOOPS_ROOT_PATH . '/include/xoopscodes.php';

    echo "<script language=\"javascript\">\nfunction CLA(CLA) { var MainWindow = window.open (CLA, \"_blank\",\"width=500,height=300,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no\");}\n</script>";

    require_once XOOPS_ROOT_PATH . '/modules/myads/class/arbre.php';

    $mytree = new XoopsArbre($xoopsDB->prefix('ann_categories'), 'cid', 'pid');

    $photomax1 = $photomax / 1024;

    $result = $xoopsDB->query('select lid, cid, title, type, description, tel, price, typeprix, date, email, submitter, usid, town, country, valid, photo from ' . $xoopsDB->prefix('ann_annonces') . " where lid=$lid");

    [$lid, $cide, $title, $type, $description, $tel, $price, $typeprix, $date, $email, $submitter, $usid, $town, $country, $valid, $photo_old] = $xoopsDB->fetchRow($result);

    if ($xoopsUser) {
        $calusern = $xoopsUser->uid();

        if ($usid == $calusern) {
            OpenTable();

            echo '<b>' . _CLA_MODIFANN . '</b><br><br>';

            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

            $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

            $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5);

            $tel = htmlspecialchars($tel, ENT_QUOTES | ENT_HTML5);

            $price = htmlspecialchars($price, ENT_QUOTES | ENT_HTML5);

            $typeprix = htmlspecialchars($typeprix, ENT_QUOTES | ENT_HTML5);

            $submitter = htmlspecialchars($submitter, ENT_QUOTES | ENT_HTML5);

            $town = htmlspecialchars($town, ENT_QUOTES | ENT_HTML5);

            $country = htmlspecialchars($country, ENT_QUOTES | ENT_HTML5);

            $useroffset = '';

            if ($xoopsUser) {
                $timezone = $xoopsUser->timezone();

                if (isset($timezone)) {
                    $useroffset = $xoopsUser->timezone();
                } else {
                    $useroffset = $xoopsConfig['default_TZ'];
                }
            }

            $dates = ($useroffset * 3600) + $date;

            $dates = formatTimestamp($date, 's');

            echo '<form action="supprann.php" method=post ENCTYPE="multipart/form-data">
		    <TABLE><TR>
			<TD>' . _CLA_NUMANNN . " </TD><TD>$lid " . _CLA_DU . " $dates</TD>
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

            echo '</select></TD>';

            echo '</TR><TR>
			<TD>' . _CLA_CAT2 . ' </TD><TD>';

            $mytree->makeMySelBox('title', 'title', $cide);

            echo '</TD>
			</TR><TR>
			<TD>' . _CLA_ANNONCE . ' </TD><TD>';

            // add XOOPS CODE by Tom (hidden)

            //echo "<textarea name=\"description\" cols=\"60\" rows=\"10\">$description</textarea></TD>";

            ob_start();

            $GLOBALS['description_text'] = $description;

            xoopsCodeTarea('description_text', 50, 6);

            $xoops_codes_tarea = ob_get_contents();

            ob_end_clean();

            echo $xoops_codes_tarea;

            echo '</TR><TR>';

            if (1 == $ynprice) {
                echo '<TR><TD>' . _CLA_PRICE2 . " </TD><TD><input type=\"text\" name=\"price\" size=\"20\" value=\"$price\"> $monnaie";

                $result3 = $xoopsDB->query('select nom_prix from ' . $xoopsDB->prefix('ann_prix') . ' order by id_prix');

                echo " <select name=\"typeprix\"><option value=\"$typeprix\">$typeprix</option>";

                while (list($nom_prix) = $xoopsDB->fetchRow($result3)) {
                    echo "<option value=\"$nom_prix\">$nom_prix</option>";
                }

                echo '</select></TD></TR>';
            }

            if ($photo_old) {
                echo '</TR><TD>' . _CLA_ACTUALPICT . " </TD><TD><A href=\"javascript:CLA('display-image.php?lid=$lid')\">$photo_old</A> <input type=\"hidden\" name=\"photo_old\" value=\"$photo_old\"> <INPUT TYPE=\"checkbox\" NAME=\"supprim\" VALUE=\"yes\"> " . _CLA_DELPICT . '</TD>
				</TR><TR>';

                echo '<TD>' . _CLA_NEWPICT . " </TD><TD><INPUT TYPE=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$photomax\"><input type=file name=\"photo\"> (<  ";

                printf('%.2f KB', $photomax1);

                echo ')</TD>';
            } else {
                echo '<TD>' . _CLA_IMG . " </TD><TD><INPUT TYPE=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$photomax\"><input type=file name=\"photo\"> (<  ";

                printf('%.2f KB', $photomax1);

                echo ')</TD>';
            }

            echo '</TR><TR>
			<TD colspan=2><input type="submit" value="' . _CLA_MODIFANN . '"></TD>
			</TR></TABLE>';

            echo '<input type="hidden" name="op" value="ModAnnonceS">';

            if ('1' == $moderated) {
                echo '<input type="hidden" name="valid" value="No">';

                echo '<br>' . _CLA_MODIFBEFORE . '<br>';
            } else {
                echo '<input type="hidden" name="valid" value="Yes">';
            }

            echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">";

            echo "<input type=\"hidden\" name=\"date\" value=\"$date\">";

            echo "<input type=\"hidden\" name=\"submitter\" value=\"$submitter\">
			</form><br>";

            CloseTable();
        }
    }
}

function ModAnnonceS($lid, $cat, $title, $type, $description, $tel, $price, $typeprix, $date, $email, $submitter, $town, $country, $valid, $photo, $photo_old, $photoS_size, $photoS_name, $HTTP_POST_FILES, $supprim)
{
    global $xoopsDB, $xoopsConfig, $photomax, $myts, $xoopsLogger;

    $destination = XOOPS_ROOT_PATH . '/modules/myads/images_ann';

    if ('yes' == $supprim) {
        if (file_exists("$destination/$photo_old")) {
            unlink("$destination/$photo_old");
        }

        $photo_old = '';
    }

    $title = $myts->addSlashes($title);

    $type = $myts->addSlashes($type);

    $description = $myts->addSlashes($description);

    $tel = $myts->addSlashes($tel);

    $price = $myts->addSlashes($price);

    $typeprix = $myts->addSlashes($typeprix);

    $submitter = $myts->addSlashes($submitter);

    $town = $myts->addSlashes($town);

    $country = $myts->addSlashes($country);

    if (!empty($HTTP_POST_FILES['photo']['name'])) {
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';

        $upload = new XoopsMediaUploader("$destination/", ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/jpg', 'image/pjpg', 'image/x-png'], $photomax);

        // for same file name Probrem  by Tom

        //$upload->setTargetFileName($HTTP_POST_FILES['photo']['name']);

        $upload->setTargetFileName($date . '_' . $HTTP_POST_FILES['photo']['name']);

        $upload->fetchMedia('photo');

        if (!$upload->upload()) {
            redirect_header("supprann.php?op=ModAnnonce&lid=$lid", 3, $upload->getErrors());

            exit();
        }

        if ($photo_old) {
            if (@file_exists("$destination/$photo_old")) {
                unlink("$destination/$photo_old");
            }
        }

        $photo_old = $upload->getSavedFileName();
    }

    $xoopsDB->query(
        'update '
        . $xoopsDB->prefix('ann_annonces')
        . " set cid='$cat', title='$title', type='$type', description='$description', tel='$tel', price='$price', typeprix='$typeprix',  email='$email', submitter='$submitter', town='$town', country='$country', valid='$valid', photo='$photo_old' where lid=$lid"
    );

    redirect_header('index.php', 1, _CLA_ANNMOD2);

    exit();
}

####################################################
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

$ok = $_GET['ok'] ?? '';

if (!isset($_POST['lid']) && isset($_GET['lid'])) {
    $lid = $_GET['lid'];
}
if (!isset($_POST['op']) && isset($_GET['op'])) {
    $op = $_GET['op'];
}

switch ($op) {
    case 'ModAnnonce':
        require XOOPS_ROOT_PATH . '/header.php';
        ModAnnonce($lid);
        require XOOPS_ROOT_PATH . '/footer.php';
        break;
    case 'ModAnnonceS':
        ModAnnonceS($lid, $cid, $title, $type, $description_text, $tel, $price, $typeprix, $date, $email, $submitter, $town, $country, $valid, $photo, $photo_old, $photo_size, $photo_name, $HTTP_POST_FILES, $supprim);
        break;
    case 'AnnoncesDel':
        require XOOPS_ROOT_PATH . '/header.php';
        AnnoncesDel($lid, $ok);
        require XOOPS_ROOT_PATH . '/footer.php';
        break;
    default:
        redirect_header('index.php', 1, '' . _RETURNANN . '');
        break;
}
