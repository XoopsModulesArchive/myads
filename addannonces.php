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

function addindex($cid)
{
    //global $xoopsDB, $xoopsConfig, $xoopsUser, $xoopsTheme, $photomax, $xoopsLogger;

    global $xoopsDB, $xoopsConfig, $xoopsUser, $xoopsTheme, $photomax, $xoopsLogger, $xoopsModule;

    require XOOPS_ROOT_PATH . '/modules/myads/cache/config.php';

    require XOOPS_ROOT_PATH . '/modules/myads/include/functions.php';

    // for XOOPS CODE  by Tom

    require XOOPS_ROOT_PATH . '/include/xoopscodes.php';

    require_once XOOPS_ROOT_PATH . '/modules/myads/class/arbre.php';

    $mytree = new XoopsArbre($xoopsDB->prefix('ann_categories'), 'cid', 'pid');

    if ('' == $cid) {
        redirect_header('index.php', 1, _CLA_ADDANNONCE);

        exit();
    }

    if (!$xoopsUser && 0 == $annoadd) {
        redirect_header(XOOPS_URL . '/user.php', 3, _CLA_FORMEMBERS2);

        exit();
    }

    $photomax1 = $photomax / 1024;

    echo '<script>
          function verify() {
                var msg = "' . _CLA_VALIDERORMSG . '\\n__________________________________________________\\n\\n";
                var errors = "FALSE";

                if (document.Add.type.value == "0") {
                        errors = "TRUE";
                        msg += "' . _CLA_VALIDTYPE . '\\n";
                }
				
                if (document.Add.cid.value == "") {
                        errors = "TRUE";
                        msg += "' . _CLA_VALIDCAT . '\\n";
                }
				
                if (document.Add.title.value == "") {
                        errors = "TRUE";
                        msg += "' . _CLA_VALIDTITLE . '\\n";
                }
				
				if (document.Add.description_text.value == "") {
                        errors = "TRUE";
                        msg += "' . _CLA_VALIDANN . '\\n";
                }
				
				if (document.Add.submitter.value == "") {
                        errors = "TRUE";
                        msg += "' . _CLA_VALIDSUBMITTER . '\\n";
                }
				
				if (document.Add.email.value == "") {
                        errors = "TRUE";
                        msg += "' . _CLA_VALIDEMAIL . '\\n";
                }
				
				if (document.Add.town.value == "" && document.Add.country.value == "") {
                        errors = "TRUE";
                        msg += "' . _CLA_VALIDTOWNCOUNTRY . '\\n";
                }
				
                if (errors == "TRUE") {
                        msg += "__________________________________________________\\n\\n' . _CLA_VALIDMSG . '\\n";
                        alert(msg);
                        return false;
                }
          }
          </script>';

    [$numrows] = $xoopsDB->fetchRow($xoopsDB->query('select cid, title from ' . $xoopsDB->prefix('ann_categories') . ''));

    if ($numrows > 0) {
        OpenTable();

        if ('1' == $moderated) {
            echo '<b>' . _CLA_ADDANNONCE3 . '</b><br><br><CENTER>' . _CLA_ANNMODERATE . " $claday " . _CLA_DAY . '</CENTER><br><br>';
        } else {
            echo '<b>' . _CLA_ADDANNONCE3 . '</b><br><br><CENTER>' . _CLA_ANNNOMODERATE . " $claday " . _CLA_DAY . '</CENTER><br><br>';
        }

        echo '<form method="post" action="addannonces.php" ENCTYPE="multipart/form-data" NAME="Add" onSubmit="return verify();">';

        echo "<TABLE width='100%' class='outer' cellspacing='1'><TR>";

        $result2 = $xoopsDB->query('select nom_type from ' . $xoopsDB->prefix('ann_type') . ' order by nom_type');

        echo "<TD class='head'>" . _CLA_TYPE . " </TD><TD class='even'><select name=\"type\"><option value=\"0\">" . _CLA_SELECTYPE . '</option>';

        while (list($nomtyp) = $xoopsDB->fetchRow($result2)) {
            echo "<option value=\"$nomtyp\">$nomtyp</option>";
        }

        echo '</select></TD>
				</TR><TR>';

        echo "<TD class='head'>" . _CLA_CAT2 . " </TD><TD class='even'>";

        $x = 0;

        $i = 0;

        $requete = $xoopsDB->query('select cid, pid, title, affprix from ' . $xoopsDB->prefix('ann_categories') . ' where  cid=' . $cid . '');

        [$ccid, $pid, $title, $affprix] = $xoopsDB->fetchRow($requete);

        $varid[$x] = $ccid;

        $varnom[$x] = $title;

        if (0 != $pid) {
            $x = 1;

            while (0 != $pid) {
                $requete2 = $xoopsDB->query('select cid, pid, title from ' . $xoopsDB->prefix('ann_categories') . ' where cid=' . $pid . '');

                [$ccid, $pid, $title] = $xoopsDB->fetchRow($requete2);

                $varid[$x] = $ccid;

                $varnom[$x] = $title;

                $x++;
            }

            $x -= 1;
        }

        while (-1 != $x) {
            echo ' &raquo; ' . $varnom[$x] . '';

            $x -= 1;
        }

        echo "<input type=\"hidden\" name=\"cid\" value=\"$cid\"></TD>
				</TR><TR>
				<TD class='head'>" . _CLA_TITLE2 . " </TD><TD class='even'><input type=\"text\" name=\"title\" size=\"50\" maxlength=\"100\"></TD>
				</TR><TR>
				<TD class='head'>" . _CLA_ANNONCE . ' <br>' . _CLA_CHARMAX . "</TD><TD class='even'>";

        // add XOOPS CODE by Tom (hidden)

        //echo "<textarea name=\"description\" cols=\"50\" rows=\"5\"></textarea>";

        $description = '';

        ob_start();

        $GLOBALS['description_text'] = $description;

        xoopsCodeTarea('description_text', 50, 6);

        $xoops_codes_tarea = ob_get_contents();

        ob_end_clean();

        echo $xoops_codes_tarea;

        echo "</TD></TR><TR>
			<td class='head'>" . _CLA_IMG . "</td><td class='even'><INPUT TYPE=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$photomax\"><input type=file name=\"photo\"> (<  ";

        printf('%.2f KB', $photomax1);

        echo ')</td></TR>';

        if (1 == $ynprice) {
            if (1 == $affprix) {
                echo "<TR><TD class='head'>" . _CLA_PRICE2 . " </TD><TD class='even'><input type=\"text\" name=\"price\" size=\"20\"> $monnaie";

                $result3 = $xoopsDB->query('select nom_prix from ' . $xoopsDB->prefix('ann_prix') . ' order by id_prix');

                echo '<select name="typeprix">';

                while (list($nom_prix) = $xoopsDB->fetchRow($result3)) {
                    echo "<option value=\"$nom_prix\">$nom_prix</option>";
                }

                echo '</select></TD>';
            }
        }

        if ($xoopsUser) {
            $iddd = $xoopsUser->getVar('uid', 'E');

            $idd = $xoopsUser->getVar('name', 'E');        // Real name

            $idde = $xoopsUser->getVar('email', 'E');

            // Add by Tom
                $iddn = $xoopsUser->getVar('uname', 'E');    // user name
        }

        $time = time();

        // CHGED name pattern by Tom

        if ($idd) {
            echo "</TR><TR>
					<TD class='head'>" . _CLA_SURNAME . " </TD><TD class='even'><input type=\"text\" name=\"submitter\" size=\"30\" value=\"$idd\"></TD>";
        } else {
            echo "</TR><TR>
					<TD class='head'>" . _CLA_SURNAME . " </TD><TD class='even'><input type=\"text\" name=\"submitter\" size=\"30\" value=\"$iddn\"></TD>";
        }

        echo "</TR><TR>
				<TD class='head'>" . _CLA_EMAIL . " </TD><TD class='even'><input type=\"text\" name=\"email\" size=\"30\" value=\"$idde\"></TD>
				</TR><TR>
				<TD class='head'>" . _CLA_TEL . " </TD><TD class='even'><input type=\"text\" name=\"tel\" size=\"30\"></TD>
				</TR><TR>
				<TD class='head'>" . _CLA_TOWN . " </TD><TD class='even'><input type=\"text\" name=\"town\" size=\"30\"></TD>
				</TR><TR>
				<TD class='head'>" . _CLA_COUNTRY . " </TD><TD class='even'><input type=\"text\" name=\"country\" size=\"30\"></TD>
				</TR></TABLE><br>
				<input type=\"hidden\" name=\"usid\" value=\"$iddd\">
				<input type=\"hidden\" name=\"op\" value=\"AddAnnoncesOk\">";

        if ('1' == $moderated) {
            echo '<input type="hidden" name="valid" value="No">';
        } else {
            echo '<input type="hidden" name="valid" value="Yes">';
        }

        echo "<input type=\"hidden\" name=\"lid\" value=\"0\">
				<input type=\"hidden\" name=\"date\" value=\"$time\">
				<input type=\"submit\" value=\"" . _CLA_VALIDATE . '">';

        echo '</form>';

        CloseTable();

        //	copyright();
            //			require XOOPS_ROOT_PATH."/footer.php";
    }
}

function AddAnnoncesOk($lid, $cat, $title, $type, $description, $tel, $price, $typeprix, $date, $email, $submitter, $usid, $town, $country, $valid, $photo, $photo_size, $photo_name, $HTTP_POST_FILES)
{
    global $xoopsDB, $xoopsConfig, $photomax, $destination, $myts, $xoopsLogger;

    require XOOPS_ROOT_PATH . '/modules/myads/cache/config.php';

    require XOOPS_ROOT_PATH . '/modules/myads/include/functions.php';

    $title = $myts->addSlashes($title);

    $type = $myts->addSlashes($type);

    $description = $myts->addSlashes($description);

    $tel = $myts->addSlashes($tel);

    $price = $myts->addSlashes($price);

    $typeprix = $myts->addSlashes($typeprix);

    $submitter = $myts->addSlashes($submitter);

    $town = $myts->addSlashes($town);

    $country = $myts->addSlashes($country);

    $filename = '';

    if (!empty($HTTP_POST_FILES['photo']['name'])) {
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';

        $upload = new XoopsMediaUploader(XOOPS_ROOT_PATH . '/modules/myads/images_ann/', ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/jpg', 'image/pjpg', 'image/x-png'], $photomax);

        // for same file name Probrem  by Tom

        //$upload->setTargetFileName($HTTP_POST_FILES['photo']['name']);

        $upload->setTargetFileName($date . '_' . $HTTP_POST_FILES['photo']['name']);

        $upload->fetchMedia('photo');

        if (!$upload->upload()) {
            redirect_header("addannonces.php?cid=$cat", 3, $upload->getErrors());

            exit();
        }

        $filename = $upload->getSavedFileName();
    }

    $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('ann_annonces') . " values ('', '$cat', '$title', '$type', '$description', '$tel', '$price', '$typeprix', '$date', '$email', '$submitter', '$usid',  '$town', '$country',  '$valid', '$filename', '0')");

    redirect_header('index.php', 1, _CLA_ANNADDED);

    exit();
}

#######################################################
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

if (!isset($_POST['cid']) && isset($_GET['cid'])) {
    $cid = $_GET['cid'];
}

if (!isset($_POST['op']) && isset($_GET['op'])) {
    $op = $_GET['op'];
}

if (!isset($op)) {
    $op = '';
}

switch ($op) {
    case 'AddAnnoncesOk':
        AddAnnoncesOk($lid, $cid, $title, $type, $description_text, $tel, $price, $typeprix, $date, $email, $submitter, $usid, $town, $country, $valid, $photo, $photo_size, $photo_name, $HTTP_POST_FILES);
        break;
    default:
        require XOOPS_ROOT_PATH . '/header.php';
        addindex($cid);
        require XOOPS_ROOT_PATH . '/footer.php';
        break;
}
