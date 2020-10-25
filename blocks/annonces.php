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

function annonces_show()
{
    global $xoopsDB, $xoopsConfig, $ynprice, $myts;

    $myts = MyTextSanitizer::getInstance();

    $block = [];

    $block['title'] = _MB_CLA_TITLE;

    require XOOPS_ROOT_PATH . '/modules/myads/cache/config.php';

    $query = 'select lid, title, type FROM ' . $xoopsDB->prefix('ann_annonces') . " WHERE valid='Yes' ORDER BY date DESC LIMIT $newclassifieds";

    $result = $xoopsDB->query($query);

    while (list($lid, $title, $type) = $xoopsDB->fetchRow($result)) {
        $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

        $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

        if (!XOOPS_USE_MULTIBYTES) {
            if (mb_strlen($title) >= 255) {
                $title = mb_substr($title, 0, 255) . '...';
            }
        }

        $a_item['type'] = $type;

        $a_item['link'] = '<a href="' . XOOPS_URL . "/modules/myads/index.php?pa=viewannonces&lid=$lid\">$title</a>";

        $block['items'][] = $a_item;
    }

    $block['link'] = '<a href="' . XOOPS_URL . '/modules/myads/">' . _MB_CLA_ALLANN . '</a></div>';

    return $block;
}
