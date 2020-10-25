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

xoops_header();
$lid = $_GET['lid'];

global $xoopsUser, $xoopsConfig, $xoopsTheme, $xoopsDB, $xoops_footer, $xoopsLogger;
$currenttheme = getTheme();

$result = $xoopsDB->query('select photo FROM ' . $xoopsDB->prefix('ann_annonces') . " WHERE lid = '$lid'");
$recordexist = $xoopsDB->getRowsNum($result);

if ($recordexist) {
    [$photo] = $xoopsDB->fetchRow($result);

    echo "<CENTER><IMG SRC=\"images_ann/$photo\" BORDER=0></CENTER>";
}

echo "<center><table><tr><td><a href=#  onClick='window.close()'>" . _CLA_CLOSEF . '</A></td></tr></table></center>';

xoops_footer();
