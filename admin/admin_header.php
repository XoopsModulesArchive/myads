<?php

include '../../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsmodule.php';
require XOOPS_ROOT_PATH . '/include/cp_functions.php';

if ($xoopsUser) {
    $xoopsModule = XoopsModule::getByDirname('myads');

    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        redirect_header(XOOPS_URL . '/', 3, _NOPERM);

        exit();
    }
} else {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);

    exit();
}
if (file_exists('../language/' . $xoopsConfig['language'] . '/admin.php')) {
    include '../language/' . $xoopsConfig['language'] . '/admin.php';
} else {
    include '../language/english/admin.php';
}
$myts = MyTextSanitizer::getInstance();

function myads_admin_menu()
{
    global $xoopsConfig, $xoopsModule;

    // language files

    $language = $xoopsConfig['language'];

    if (!file_exists(XOOPS_ROOT_PATH . '/modules/system/language/' . $language . '/admin/blocksadmin.php')) {
        $language = 'english';
    }

    require_once XOOPS_ROOT_PATH . '/modules/system/language/' . $language . '/admin.php';

    require_once XOOPS_ROOT_PATH . '/modules/system/language/' . $language . '/admin/blocksadmin.php';

    // link to pref.php   add by Tom

    // link to myblocksadmin.php add by Tom Thanks GIJ

    echo "<h3 style='text-align:left;'>" . $xoopsModule->name() . "</h3>\n";

    echo "<table width='100%' border='0' cellspacing='1' cellpadding='3' class='outer'><td class='even'>";

    echo '<A HREF="index.php">' . _CLA_CONF . '</A>';

    echo "</td><td class='even'>";

    echo '<A HREF="map.php">' . _CLA_GESTCAT . '</A>';

    echo "</td><td class='even'>";

    echo '<A HREF="pref.php">' . _MD_AM_PREF . '</A>';

    echo "</td><td class='even'>";

    echo '<A HREF="myblocksadmin.php">' . _AM_BADMIN . '</A>';

    CloseTable();

    echo '<br>';
}
