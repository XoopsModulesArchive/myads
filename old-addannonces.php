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
    global $xoopsDB, $xoopsConfig, $xoopsUser, $xoopsTheme;

    require XOOPS_ROOT_PATH . '/modules/myAds/cache/config.php';

    require XOOPS_ROOT_PATH . '/modules/myAds/include/functions.php';

    require_once XOOPS_ROOT_PATH . '/modules/myAds/class/arbre.php';

    $mytree = new XoopsArbre($xoopsDB->prefix('ann_categories'), 'cid', 'pid');

    if ('' == $cid) {
        redirect_header('index.php', 1, _CLA_ADDANNONCE);

        exit();
    }

    if (!$xoopsUser && 0 == $annoadd) {
        require XOOPS_ROOT_PATH . '/header.php';

        OpenTable();

        echo '<DIV ALIGN="center">' . _CLA_FORMEMBERS . ' <A HREF="' . XOOPS_URL . '/register.php">' . _CLA_REGISTER . '</A><br>' . _CLA_OR . ' <A HREF="' . XOOPS_URL . '/user.php">' . _CLA_CONECT . '</A> ' . _CLA_IFAREMEMBER . '</DIV>';

        CloseTable();

        copyright();

        require XOOPS_ROOT_PATH . '/footer.php';
    } else {
        $photomax1 = $photomax / 1024;

        require XOOPS_ROOT_PATH . '/header.php';

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
				
                if (document.Add.titre.value == "") {
                        errors = "TRUE";
                        msg += "' . _CLA_VALIDTITLE . '\\n";
                }
				
				if (document.Add.description.value == "") {
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
                echo '<b>' . _CLA_ADDANNONCE . '</b><br><br><CENTER>' . _CLA_ANNMODERATE . " $claday " . _CLA_DAY . '</CENTER><br><br>';
            } else {
                echo '<b>' . _CLA_ADDANNONCE . '</b><br><br><CENTER>' . _CLA_ANNNOMODERATE . " $claday " . _CLA_DAY . '</CENTER><br><br>';
            }

            echo '<form method="post" action="addannonces.php" ENCTYPE="multipart/form-data" NAME="Add" onSubmit="return verify();">';

            echo '<TABLE><TR>';

            $result2 = $xoopsDB->query('select nom_type from ' . $xoopsDB->prefix('ann_type') . ' order by nom_type');

            echo '<TD valign=top>' . _CLA_TYPE . ' </TD><TD><select name="type"><option value="0">' . _CLA_SELECTYPE . '</option>';

            while (list($nomtyp) = $xoopsDB->fetchRow($result2)) {
                echo "<option value=\"$nomtyp\">$nomtyp</option>";
            }

            echo '</select></TD>
		</TR><TR>';

            echo '<TD valign=top>' . _CLA_CAT2 . ' </TD><TD>';

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
		<TD>" . _CLA_TITLE2 . ' </TD><TD><input type="text" name="titre" size="50" maxlength="100"></TD>
		</TR><TR>
		<TD>' . _CLA_ANNONCE . ' <br>' . _CLA_CHARMAX . '</TD><TD><textarea name="description" cols="50" rows="5"></textarea></TD>
		</TR><TR>
		<td>' . _CLA_IMG . '</td><td><INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="10000000"><input type=file name="photo"> (<  ';

            printf('%.2f ko', $photomax1);

            echo ')</td></TR>';

            if (1 == $ynprice) {
                if (1 == $affprix) {
                    echo '<TR><TD>' . _CLA_PRICE2 . " </TD><TD><input type=\"text\" name=\"price\" size=\"20\"> $monnaie";

                    $result3 = $xoopsDB->query('select nom_prix from ' . $xoopsDB->prefix('ann_prix') . ' order by id_prix');

                    echo ' <select name="typeprix">';

                    while (list($nom_prix) = $xoopsDB->fetchRow($result3)) {
                        echo "<option value=\"$nom_prix\">$nom_prix</option>";
                    }

                    echo '</select></TD>';
                }
            }

            if ($xoopsUser) {
                $iddd = $xoopsUser->getVar('uid', 'E');

                $idd = $xoopsUser->getVar('name', 'E');

                $idde = $xoopsUser->getVar('email', 'E');
            }

            $time = time();

            echo '</TR><TR>
		<TD>' . _CLA_SURNAME . " </TD><TD><input type=\"text\" name=\"submitter\" size=\"30\" value=\"$idd\"></TD>
		</TR><TR>
		<TD>" . _CLA_EMAIL . " </TD><TD><input type=\"text\" name=\"email\" size=\"30\" value=\"$idde\"></TD>
		</TR><TR>
		<TD>" . _CLA_TEL . ' </TD><TD><input type="text" name="tel" size="30"></TD>
		</TR><TR>
		<TD>' . _CLA_TOWN . ' </TD><TD><input type="text" name="town" size="30"></TD>
		</TR><TR>
		<TD>' . _CLA_COUNTRY . " </TD><TD><input type=\"text\" name=\"country\" size=\"30\"></TD>
		</TR></TABLE>
		<input type=\"hidden\" name=\"usid\" value=\"$iddd\">
		<input type=\"hidden\" name=\"op\" value=\"AddAnnoncesOk\">";

            if ('1' == $moderated) {
                echo '<input type="hidden" name="valid" value="No">';
            } else {
                echo '<input type="hidden" name="valid" value="Yes">';
            }

            echo "<input type=\"hidden\" name=\"lid\" value=\"0\">
		<input type=\"hidden\" name=\"date\" value=\"$time\">
		<input type=\"submit\" value=\"" . _CLA_VALIDATE . '">
	    </form>';

            CloseTable();

            copyright();

            require XOOPS_ROOT_PATH . '/footer.php';
        }
    }
}

function AddAnnoncesOk($lid, $cat, $titre, $type, $description, $tel, $price, $typeprix, $date, $email, $submitter, $usid, $town, $country, $valid, $photo, $photo_size, $photo_name)
{
    global $xoopsDB, $xoopsConfig, $photomax, $destination, $myts;

    require XOOPS_ROOT_PATH . '/modules/myAds/cache/config.php';

    require XOOPS_ROOT_PATH . '/modules/myAds/include/functions.php';

    //echo "$photo_size - $photomax - $photo - $photo_name";

    if ($photo_name) {
        $typephoto[1] = 'gif';

        $typephoto[2] = 'jpg';

        $typephoto[3] = 'png';

        $typephoto[4] = 'GIF';

        $typephoto[5] = 'JPG';

        $typephoto[6] = 'PNG';

        preg_match("\.([^\.]*$)", $photo_name, $elts);

        $extension_fichier = $elts[1];

        if (!in_array($extension_fichier, $typephoto, true)) {
            require XOOPS_ROOT_PATH . '/header.php';

            OpenTable();

            echo '' . _CLA_FILES . " $extension_fichier " . _CLA_FILESTOP . '.';

            CloseTable();

            copyright();

            require XOOPS_ROOT_PATH . '/footer.php';

            exit();
        }

        if ($photo_size > $photomax) {
            $photomax1 = $photomax / 1024;

            require XOOPS_ROOT_PATH . '/header.php';

            OpenTable();

            echo '' . _CLA_YIMG . ' ' . $photo_name . ' ' . _CLA_TOBIG . ' < ';

            printf('%.2f ko', $photomax1);

            CloseTable();

            copyright();

            require XOOPS_ROOT_PATH . '/footer.php';

            exit();
        }

        $photnom = "$date$photo_name";

        $destination = XOOPS_ROOT_PATH . "/modules/myAds/images_ann/$photnom";

        if (!copy((string)$photo, $destination)) {
            require XOOPS_ROOT_PATH . '/header.php';

            OpenTable();

            echo '' . _CLA_JOIND . '.';

            CloseTable();

            copyright();

            require XOOPS_ROOT_PATH . '/footer.php';

            exit();
        }
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

    $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('ann_annonces') . " values ('', '$cat', '$titre', '$type', '$description', '$tel', '$price', '$typeprix', '$date', '$email', '$submitter', '$usid',  '$town', '$country',  '$valid', '$photnom', '0')");

    redirect_header('index.php', 1, _CLA_ANNADDED);

    exit();
}

switch ($op) {
    case 'AddAnnoncesOk':
        AddAnnoncesOk($lid, $cid, $titre, $type, $description, $tel, $price, $typeprix, $date, $email, $submitter, $usid, $town, $country, $valid, $photo, $photo_size, $photo_name);
        break;
    default:
        addindex($cid);
        break;
}
