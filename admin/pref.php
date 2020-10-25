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

function Conf()
{
    global $xoopsUser, $xoopsConfig, $xoopsModule;

    global $annoadd, $justuser, $nb_affichage, $monnaie, $newclassifieds, $moderated, $photomax, $claday, $affichebloc, $countday, $ynprice, $souscat, $classm, $nbsouscat, $newann;

    xoops_cp_header();

    myads_admin_menu();

    OpenTable();

    echo '<b>' . _CLA_CONFMYA . '</b><br><br>';

    echo '<FORM ACTION="pref.php?pa=ConfOk" METHOD=POST>';

    echo '<table width = "100%" border = "0"><tr>
	<td>' . _CLA_ANNOCANPOST . ' </td>
	<td><input type="radio" name="annoaddS" value="1"';

    if ('1' == $annoadd) {
        echo 'checked';
    }

    echo '>' . _CLA_OUI . '&nbsp;&nbsp; <input type="radio" name="annoaddS" value="0"';

    if ('0' == $annoadd) {
        echo 'checked';
    }

    echo '>' . _CLA_NON . '</td>
	</tr><tr>
	<td>' . _CLA_PERPAGE . " </td>
	<td><select name=nb_affichageS>
	<option value=$nb_affichage selected>$nb_affichage</option>
	<option value=10>10</option>
	<option value=15>15</option>
	<option value=20>20</option>
	<option value=25>25</option>
	<option value=30>30</option>
	<option value=50>50</option>
	</select></td>
	</tr><tr>
	<td>" . _CLA_MONEY . " </td>
	<td><input type=\"text\" name=\"monnaieS\" value=\"$monnaie\" size=\"3\" maxlength=\"2\"></td>
	</tr>
	<tr>
	<td>" . _CLA_VIEWNEWCLASS . ' </td>
	<td><input type="radio" name="newannS" value="1"';

    if ('1' == $newann) {
        echo 'checked';
    }

    echo '>' . _CLA_OUI . '&nbsp;&nbsp; <input type="radio" name="newannS" value="0"';

    if ('0' == $newann) {
        echo 'checked';
    }

    echo '>' . _CLA_NON . ' (' . _CLA_ONHOME . ')</td>
	</tr>
	<tr>
	<td>' . _CLA_NUMNEW . " </td>
	<td><select name=newclassifiedsS>
	<option value=$newclassifieds>$newclassifieds</option>
	<option value=5>5</option>
	<option value=10>10</option>
	<option value=15>15</option>
	<option value=20>20</option>
	<option value=25>25</option>
	<option value=30>30</option>
	<option value=50>50</option>
	</select> (" . _CLA_ONHOME . ')</td>
	</tr>
	<tr>
	<td>' . _CLA_MODERAT . ' </td>
	<td><input type="radio" name="moderatedS" value="1"';

    if ('1' == $moderated) {
        echo 'checked';
    }

    echo '>' . _CLA_OUI . '&nbsp;&nbsp; <input type="radio" name="moderatedS" value="0"';

    if ('0' == $moderated) {
        echo 'checked';
    }

    echo '>' . _CLA_NON . '</td>
	</tr>
	<tr>
	<td>' . _CAL_MAXIIMGS . " </td>
	<td><input type=\"text\" name=\"photomaxS\" value=\"$photomax\" size=\"10\"> (" . _CLA_INOCTET . ')</td>
	</tr>
	<tr>
	<td>' . _CLA_TIMEANN . " </td>
	<td><input type=\"text\" name=\"cladayS\" value=\"$claday\" size=\"4\" maxlength=\"4\"> (" . _CLA_INDAYS . ')</td>
	</tr>
	<tr>
	<td>' . _CLA_TYPEBLOC . ' </td>
	<td><SELECT NAME="afficheblocS">
	<OPTION VALUE="1"';

    if ('1' == $affichebloc) {
        echo ' selected';
    }

    echo '> ' . _CLA_LASTTEN . '
	<OPTION VALUE="2"';

    if ('2' == $affichebloc) {
        echo ' selected';
    }

    echo '> ' . _CLA_ANNRAND . '
	</SELECT></td>
	</tr>
	<tr>
	<td>' . _CLA_NEWTIME . " </td>
	<td><select name=countdayS>
	<option value=$countday>$countday</option>
	<option value=1>1</option>
	<option value=2>2</option>
	<option value=3>3</option>
	<option value=4>4</option>
	<option value=5>5</option>
	<option value=6>6</option>
	<option value=7>7</option>
	<option value=8>8</option>
	<option value=9>9</option>
	<option value=10>10</option>
	</select> (" . _CLA_INDAYS . ')</td>
	</tr>
	<tr>
	<td>' . _CLA_DISPLPRICE . ' </td>
	<td><input type="radio" name="ynpriceS" value="1"';

    if ('1' == $ynprice) {
        echo 'checked';
    }

    echo '>' . _CLA_OUI . '&nbsp;&nbsp; <input type="radio" name="ynpriceS" value="0"';

    if ('0' == $ynprice) {
        echo 'checked';
    }

    echo '>' . _CLA_NON . '</td>
	</tr>
	<tr>
	<td>' . _CLA_DISPLSUBCAT . ' </td>
	<td><input type="radio" name="souscatS" value="1"';

    if ('1' == $souscat) {
        echo 'checked';
    }

    echo '>' . _CLA_OUI . '&nbsp;&nbsp; <input type="radio" name="souscatS" value="0"';

    if ('0' == $souscat) {
        echo 'checked';
    }

    echo '>' . _CLA_NON . ' (' . _CLA_ONHOME . ')</td>
	</tr><tr>
	<td>' . _CLA_NBDISPLSUBCAT . " </td>
	<td><input type=\"text\" name=\"nbsouscatS\" value=\"$nbsouscat\" size=\"4\" maxlength=\"4\"> (" . _CLA_IF . ' "' . _CLA_DISPLSUBCAT . '" ' . _CLA_ISAT . ' "' . _CLA_OUI . '")</td>
	</tr>
	<tr>
	<td>' . _CLA_ORDRECLASS . ' </td>
	<td><select name=classmS>
	<option value=title';

    if ('title' == $classm) {
        echo ' selected';
    }

    echo '>' . _CLA_ORDREALPHA . '</option>
	<option value=ordre';

    if ('ordre' == $classm) {
        echo ' selected';
    }

    echo '>' . _CLA_ORDREPERSO . '</option>
	</select></td>
	</tr>
	</table><br>
	<input type="submit" value="' . _CLA_SAVMOD . '">
	</form>';

    CloseTable();

    xoops_cp_footer();
}

function ConfOK($annoaddS, $justuserS, $nb_affichageS, $monnaieS, $newclassifiedsS, $moderatedS, $photomaxS, $cladayS, $afficheblocS, $countdayS, $ynpriceS, $souscatS, $classmS, $nbsouscatS, $newannS)
{
    $file = fopen(XOOPS_ROOT_PATH . '/modules/myads/cache/config.php', 'wb');

    $content = "<?php\n";

    $content .= "// \n";

    $content .= "// ------------------------------------------------------------------------- //\n";

    $content .= "//               E-Xoops: Content Management for the Masses                  //\n";

    $content .= "//                       < http://www.e-xoops.com >                          //\n";

    $content .= "// ------------------------------------------------------------------------- //\n";

    $content .= "// Original Author: Pascal Le Boustouller\n";

    $content .= "// Author Website : pascal.e-xoops@perso-search.com\n";

    $content .= "// Licence Type   : GPL\n";

    $content .= "// ------------------------------------------------------------------------- //\n";

    $content .= "\$annoadd = $annoaddS;\n\n";

    $content .= "\$nb_affichage = $nb_affichageS;\n\n";

    $content .= "\$monnaie = \"$monnaieS\";\n\n";

    $content .= "\$newclassifieds = $newclassifiedsS;\n\n";

    $content .= "\$moderated = $moderatedS;\n\n";

    $content .= "\$photomax = $photomaxS;\n\n";

    $content .= "\$claday = $cladayS;\n\n";

    $content .= "\$affichebloc = $afficheblocS;\n\n";

    $content .= "\$countday = $countdayS;\n\n";

    $content .= "\$ynprice = $ynpriceS;\n\n";

    $content .= "\$souscat = $souscatS;\n\n";

    $content .= "\$nbsouscat = \"$nbsouscatS\";\n\n";

    $content .= "\$classm = \"$classmS\";\n\n";

    $content .= "\$newann = \"$newannS\";\n\n";

    $content .= '?>';

    fwrite($file, $content);

    fclose($file);

    redirect_header('pref.php', 1, _CLA_CONFSAVE);

    exit();
}

#######################################################
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

$pa = $_GET['pa'] ?? '';

switch ($pa) {
    case 'ConfOk':
        ConfOK($annoaddS, $justuserS, $nb_affichageS, $monnaieS, $newclassifiedsS, $moderatedS, $photomaxS, $cladayS, $afficheblocS, $countdayS, $ynpriceS, $souscatS, $classmS, $nbsouscatS, $newannS);
        break;
    default:
        Conf();
        break;
}
