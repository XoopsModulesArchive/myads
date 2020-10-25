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
require XOOPS_ROOT_PATH . '/modules/myads/include/functions.php';

require_once XOOPS_ROOT_PATH . '/modules/myads/class/arbre.php';
$mytree = new XoopsArbre($xoopsDB->prefix('ann_categories'), 'cid', 'pid');

global $mytree, $classm, $xoopsDB;

xoops_cp_header();
myads_admin_menu();

OpenTable();
echo '<B>' . _CLA_GESTCAT . '</B><br><br>';
echo '<A HREF="gest-cat.php?op=AnnoncesNewCat&cid=0"><IMG SRC="' . XOOPS_URL . '/modules/myads/images/plus.gif" BORDER=0 WIDTH=10 HEIGHT=10  alt="' . _CLA_ADDSUBCAT . '"></A> ' . _CLA_ADDCATPRINC . '<br><br>';

$mytree->makeMapSelBox('title', (string)$classm);

echo '<P><HR>';

echo _CLA_HELP1 . ' <P>';

if ('ordre' == $classm) {
    echo _CLA_HELP2 . ' <P>';
}
CloseTable();
xoops_cp_footer();
