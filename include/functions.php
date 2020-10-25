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

function showNew()
{
    global $myts, $xoopsDB, $xoopsTpl, $mf, $xoopsUser, $newclassifieds, $monnaie, $ynprice;

    require_once XOOPS_ROOT_PATH . '/modules/myads/class/arbre.php';

    $mytree = new XoopsArbre($xoopsDB->prefix('ann_categories'), 'cid', 'pid');

    // Add 'typeprix' by Tom

    $result = $xoopsDB->query('select lid, title, type, price, typeprix, date, town, country, valid, photo, view FROM ' . $xoopsDB->prefix('ann_annonces') . " WHERE valid='Yes' ORDER BY date DESC LIMIT $newclassifieds");

    if ($result) {
        $xoopsTpl->assign('last_head', _CLA_THE . " $newclassifieds " . _CLA_LASTADD);

        $xoopsTpl->assign('last_head_title', _CLA_TITLE);

        $xoopsTpl->assign('last_head_price', _CLA_PRICE);

        $xoopsTpl->assign('last_head_date', _CLA_DATE);

        $xoopsTpl->assign('last_head_local', _CLA_LOCAL2);

        $xoopsTpl->assign('last_head_views', _CLA_VIEW);

        $xoopsTpl->assign('last_head_photo', _CLA_PHOTO);

        $rank = 1;

        // Add $typeprix by Tom

        while (list($lid, $title, $type, $price, $typeprix, $date, $town, $country, $valid, $photo, $vu) = $xoopsDB->fetchRow($result)) {
            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

            $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

            $price = htmlspecialchars($price, ENT_QUOTES | ENT_HTML5);

            $town = htmlspecialchars($town, ENT_QUOTES | ENT_HTML5);

            $country = htmlspecialchars($country, ENT_QUOTES | ENT_HTML5);

            $a_item = [];

            $useroffset = '';

            if ($xoopsUser) {
                $timezone = $xoopsUser->timezone();

                if (isset($timezone)) {
                    $useroffset = $xoopsUser->timezone();
                } else {
                    $useroffset = $xoopsConfig['default_TZ'];
                }
            }

            $date = ($useroffset * 3600) + $date;

            $date = formatTimestamp($date, 's');

            if ($xoopsUser) {
                if ($xoopsUser->isAdmin()) {
                    $a_item['admin'] = "<a href='admin/index.php?op=AnnoncesModAnnonce&lid=$lid'><img src='images/modif.gif' border=0 alt=\"" . _CLA_MODADMIN . '"></a>';
                }
            }

            $a_item['type'] = $type;

            $a_item['title'] = "<a href='index.php?pa=viewannonces&lid=$lid'>$title</a>";

            if (1 == $ynprice) {
                if ($price > 0) {
                    $a_item['price'] = "$price $monnaie";

                    // Add $price_typeprix by Tom

                    $a_item['price_typeprix'] = (string)$typeprix;
                } else {
                    $a_item['price'] = '';

                    $a_item['price_typeprix'] = (string)$typeprix;
                }
            }

            $a_item['date'] = $date;

            $a_item['local'] = '';

            if ($town) {
                $a_item['local'] .= $town;
            }

            if ($country) {
                $a_item['local'] .= $country;
            }

            if ($photo) {
                $a_item['photo'] = "<a href=\"javascript:CLA('display-image.php?lid=$lid')\"><img src=\"images/photo.gif\" border=0 width=15 height=11 alt='" . _CLA_IMGPISP . "'></a>";
            }

            $a_item['views'] = $vu;

            $rank++;

            $xoopsTpl->append('items', $a_item);
        }
    }
}

function showViewAnnonces($debut, $cid, $nb_affichage, $nbe)
{
    global $xoopsDB, $xoopsConfig, $xoopsTpl, $monnaie, $newclassifieds, $xoopsUser, $ynprice, $myts;

    // Add 'typeprix' by Tom

    //$result3=$xoopsDB->query("select lid, cid, title, type, price, date, town, country, valid, photo, view from ".$xoopsDB->prefix("ann_annonces")." where  valid='yes' AND cid=$cid order by date DESC  LIMIT $debut,$nb_affichage");

    $result3 = $xoopsDB->query('select lid, cid, title, type, price, typeprix, date, town, country, valid, photo, view from ' . $xoopsDB->prefix('ann_annonces') . " where  valid='yes' AND cid=$cid order by date DESC  LIMIT $debut,$nb_affichage");

    $xoopsTpl->assign('data_rows', $nbe);

    if ('0' == $nbe) {
        $xoopsTpl->assign('no_data', _CLA_NOANNINCAT);
    } else {
        $xoopsTpl->assign('last_head', _CLA_THE . " $newclassifieds " . _CLA_LASTADD);

        $xoopsTpl->assign('last_head_title', _CLA_TITLE);

        $xoopsTpl->assign('last_head_price', _CLA_PRICE);

        $xoopsTpl->assign('last_head_date', _CLA_DATE);

        $xoopsTpl->assign('last_head_local', _CLA_LOCAL2);

        $xoopsTpl->assign('last_head_views', _CLA_VIEW);

        $xoopsTpl->assign('last_head_photo', _CLA_PHOTO);

        $rank = 1;

        // Add 'typeprix' by Tom

        //while(list($lid, $cid, $title, $type, $price, $date, $town, $country, $valid, $photo, $vu)=$xoopsDB->fetchRow($result3))

        while (list($lid, $cid, $title, $type, $price, $typeprix, $date, $town, $country, $valid, $photo, $vu) = $xoopsDB->fetchRow($result3)) {
            $a_item = [];

            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

            $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

            $price = htmlspecialchars($price, ENT_QUOTES | ENT_HTML5);

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

            $date = ($useroffset * 3600) + $date;

            $date = formatTimestamp($date, 's');

            if ($xoopsUser) {
                if ($xoopsUser->isAdmin()) {
                    $a_item['admin'] = "<a href='admin/index.php?op=AnnoncesModAnnonce&lid=$lid'><img src='images/modif.gif' border=0 alt=\"" . _CLA_MODADMIN . '"></a>';
                }
            }

            $a_item['type'] = $type;

            $a_item['title'] = "<a href='index.php?pa=viewannonces&lid=$lid'>$title</a>";

            if (1 == $ynprice) {
                if ($price > 0) {
                    $a_item['price'] = "$price $monnaie";

                    // Add $price_typeprix by Tom

                    $a_item['price_typeprix'] = (string)$typeprix;
                } else {
                    $a_item['price'] = '';

                    $a_item['price_typeprix'] = (string)$typeprix;
                }
            }

            $a_item['date'] = $date;

            $a_item['local'] = '';

            if ($town) {
                $a_item['local'] .= $town;
            }

            if ($country) {
                $a_item['local'] .= $country;
            }

            if ($photo) {
                $a_item['photo'] = "<a href=\"javascript:CLA('display-image.php?lid=$lid')\"><img src=\"images/photo.gif\" border=0 width=15 height=11 alt='" . _CLA_IMGPISP . "'></a>";
            }

            $a_item['views'] = $vu;

            $rank++;

            $xoopsTpl->append('items', $a_item);
        }
    }
}

function SupprClaDay()
{
    //for xoops2//

    include './cache/config.php';

    global $xoopsDB, $claday, $xoopsConfig, $myts, $meta;

    $datenow = time();

    $result5 = $xoopsDB->query('select lid, title, type, description, date, email, submitter, photo, view FROM ' . $xoopsDB->prefix('ann_annonces') . " WHERE valid='Yes'");

    while (list($lids, $title, $type, $description, $dateann, $email, $submitter, $photo, $lu) = $xoopsDB->fetchRow($result5)) {
        $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

        $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

        $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5);

        $submitter = htmlspecialchars($submitter, ENT_QUOTES | ENT_HTML5);

        $supprdate = $dateann + ($claday * 86400);

        if ($supprdate < $datenow) {
            //for xoops2//	$xoopsDB->query("delete from ".$xoopsDB->prefix("ann_annonces")." where lid='$lids'");

            $xoopsDB->queryF('delete from ' . $xoopsDB->prefix('ann_annonces') . " where lid='$lids'");

            $destination = XOOPS_ROOT_PATH . '/modules/myads/images_ann';

            if ($photo) {
                if (file_exists("$destination/$photo")) {
                    unlink("$destination/$photo");
                }
            }

            //	Specification for Japan:

            //	$message = ""._CLA_HELLO." $submitter,\n\n"._CLA_STOP2."\n $type : $title\n $description\n"._CLA_STOP3."\n\n"._CLA_VU." $lu "._CLA_VU2."\n\n"._CLA_OTHER." ".XOOPS_URL."/modules/myads\n\n"._CLA_THANK."\n\n"._CLA_TEAM." ".$meta['title']."\n".XOOPS_URL."";

            if ($email) {
                $message = "$submitter "
                           . _CLA_HELLO
                           . " \n\n"
                           . _CLA_STOP2
                           . "\n $type : $title\n $description\n"
                           . _CLA_STOP3
                           . "\n\n"
                           . _CLA_VU
                           . " $lu "
                           . _CLA_VU2
                           . "\n\n"
                           . _CLA_OTHER
                           . ' '
                           . XOOPS_URL
                           . "/modules/myads\n\n"
                           . _CLA_THANK
                           . "\n\n"
                           . _CLA_TEAM
                           . ' '
                           . $meta['title']
                           . "\n"
                           . XOOPS_URL
                           . '';

                $subject = '' . _CLA_STOP . '';

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
        }
    }
}

function copyright()
{
    global $xoopsTpl;

    require XOOPS_ROOT_PATH . '/modules/myads/xoops_version.php';

    $cr_developed = 'myads ' . $modversion['version'] . ' ' . _CLA_FOR . ' E-Xoops ' . _CLA_CREATBY . ' <a href="http://www.perso-search.com/e-xoopsien/" target=//"_blank">Pascal Le Boustouller</a>';

    $cr_redesigned = 'redesigned for XOOPS 2 by glassJAw and jojup from <a href="http://www.myxoops.de " target="_blank">myxoops.de team</a> /
				bugfix and Japanese Version by Tom from japan.';

    if (isset($GLOBALS['xoopsOption']['template_main'])) {
        //		$xoopsTpl->assign('cr_developed', $cr_developed);
        //		$xoopsTpl->assign('cr_redesigned', $cr_redesigned);
    }
}

function getTotalItems($sel_id, $status = '')
{
    global $xoopsDB, $mytree;

    $pfx = $xoopsDB->prefix('ann_annonces');

    $count = 1;

    $arr = [];

    $status_q = '';

    if ($status) {
        if (_YES == $status) {
            $status_q = " and valid='Yes'";
        } else {
            $status_q = " and valid='No'";
        }
    }

    $query = "select lid from $pfx where cid=" . $sel_id . (string)$status_q;

    $result = $xoopsDB->query($query);

    $count = $xoopsDB->getRowsNum($result);

    $arr = $mytree->getAllChildId($sel_id);

    $size = count($arr);

    for ($i = 0; $i < $size; $i++) {
        $query2 = "select lid from $pfx where cid=" . $arr[$i] . (string)$status_q;

        $result2 = $xoopsDB->query($query2);

        $count += $xoopsDB->getRowsNum($result2);
    }

    return $count;
}

function ShowImg()
{
    echo "<SCRIPT type=\"text/javascript\">\n";

    echo "<!--\n\n";

    echo "function showimage() {\n";

    echo "if (!document.images)\n";

    echo "return\n";

    echo "document.images.avatar.src=\n";

    echo "'" . XOOPS_URL . "/modules/myads/images/cat/' + document.imcat.img.options[document.imcat.img.selectedIndex].value\n";

    echo "}\n\n";

    echo "//-->\n";

    echo "</SCRIPT>\n";
}
