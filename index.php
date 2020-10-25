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
require XOOPS_ROOT_PATH . '/modules/myads/include/functions.php';
require_once XOOPS_ROOT_PATH . '/modules/myads/class/arbre.php';

$mytree = new XoopsArbre($xoopsDB->prefix('ann_categories'), 'cid', 'pid');

if ('myads' == $xoopsConfig['startpage']) {
    $xoopsOption['show_rblock'] = 1;

    require XOOPS_ROOT_PATH . '/header.php';

    make_cblock();
} else {
    $xoopsOption['show_rblock'] = 0;

    require XOOPS_ROOT_PATH . '/header.php';
}

/**
 *  function index
 **/
function index()
{
    global $xoopsDB, $xoopsConfig, $xoopsUser, $xoopsTpl, $moderated, $myts, $mytree, $souscat, $classm, $nbsouscat, $meta, $newann;

    $GLOBALS['xoopsOption']['template_main'] = 'myads_index.html';

    $xoopsTpl->assign('add_from', _CLA_ADDFROM . ' ' . $xoopsConfig['sitename']);

    // Add Template assign  by Tom

    $xoopsTpl->assign('add_from_title', _CLA_ADDFROM);

    $xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);

    if ('1' == $moderated) {
        $result = $xoopsDB->query('select  COUNT(*)  FROM ' . $xoopsDB->prefix('ann_annonces') . " WHERE valid='No'");

        [$propo] = $xoopsDB->fetchRow($result);

        $xoopsTpl->assign('moderated', true);

        if ($xoopsUser) {
            if ($xoopsUser->isAdmin()) {
                $xoopsTpl->assign('admin_block', _CLA_ADMINCADRE);

                if (0 == $propo) {
                    $xoopsTpl->assign('confirm_ads', _CLA_NO_CLA);
                } else {
                    $xoopsTpl->assign('confirm_ads', _CLA_THEREIS . " $propo  " . _CLA_WAIT . '<br><a href="admin/index.php">' . _CLA_SEEIT . '</a>');
                }
            }
        }
    }

    $result = $xoopsDB->query('select cid, title, img FROM ' . $xoopsDB->prefix('ann_categories') . " WHERE pid = 0 ORDER BY $classm") || die('Error');

    [$ncatp] = $xoopsDB->fetchRow($xoopsDB->query('select  COUNT(*)  FROM ' . $xoopsDB->prefix('ann_categories') . ' WHERE pid=0'));

    $count = 0;

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $a_cat = [];

        $cid = $myrow['cid'];

        $title = htmlspecialchars($myrow['title'], ENT_QUOTES | ENT_HTML5);

        $totallink = getTotalItems($myrow['cid'], _YES);

        $a_cat['image'] = "<img src='" . XOOPS_URL . '/modules/myads/images/cat/' . $myrow['img'] . "' align='absmiddle'>";

        $a_cat['link'] = '<a href="index.php?pa=view&cid=' . $myrow['cid'] . "\"><b>$title</b></a>";

        $a_cat['count'] = $totallink;

        if (1 == $souscat) {
            // get child category objects

            $arr = [];

            $arr = $mytree->getFirstChild($myrow['cid'], (string)$classm);

            $space = 0;

            $chcount = 1;

            $subcat = '';

            foreach ($arr as $ele) {
                $chtitle = htmlspecialchars($ele['title'], ENT_QUOTES | ENT_HTML5);

                if ($chcount > $nbsouscat) {
                    $subcat .= ', ...';

                    break;
                }

                if ($space > 0) {
                    $subcat .= ', ';
                }

                $subcat .= '<a href="index.php?pa=view&cid=' . $ele['cid'] . '">' . $chtitle . '</a>';

                $space++;

                $chcount++;

                $a_cat['subcat'] = $subcat;
            }
        }

        $bis = ($ncatp + 1) / 2;

        $bis = (int)$bis;

        $a_cat['i'] = $count;

        $xoopsTpl->append('categories', $a_cat);

        $count++;
    }

    $xoopsTpl->assign('cat_count', $count - 1);

    [$ann] = $xoopsDB->fetchRow($xoopsDB->query('select  COUNT(*)  FROM ' . $xoopsDB->prefix('ann_annonces') . " WHERE valid='Yes'"));

    [$catt] = $xoopsDB->fetchRow($xoopsDB->query('select  COUNT(*)  FROM ' . $xoopsDB->prefix('ann_categories') . ''));

    $xoopsTpl->assign('total_annonces', _CLA_ACTUALY . " $ann " . _CLA_ANNONCES . ' ' . _CLA_INCAT . " $catt " . _CLA_CAT2);

    if ('1' == $moderated) {
        $xoopsTpl->assign('total_confirm', _CLA_AND . " $propo " . _CLA_WAIT3);
    }

    if (1 == $newann) {
        showNew();
    }

    copyright();

    SupprClaDay();
}

/**
 *  function view (categories)
 * @param mixed $cid
 * @param mixed $debut
 **/
function view($cid, $debut)
{
    global $xoopsDB, $xoopsTpl, $xoopsConfig, $nb_affichage, $myts, $mytree, $imagecat, $classm, $meta;

    $GLOBALS['xoopsOption']['template_main'] = 'myads_category.html';

    require XOOPS_ROOT_PATH . '/modules/myads/class/nav.php';

    $xoopsTpl->assign('add_from', _CLA_ADDFROM . ' ' . $xoopsConfig['sitename']);

    // Add Template assign  by Tom

    $xoopsTpl->assign('add_from_title', _CLA_ADDFROM);

    $xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);

    $xoopsTpl->assign('add_annonce', "<a href='addannonces.php?cid=$cid'>" . _CLA_ADDANNONCE2 . '</a>');

    $count = 0;

    if (!$debut) {
        $debut = 0;
    }

    $x = 0;

    $i = 0;

    $requete = $xoopsDB->query('select cid, pid, title from ' . $xoopsDB->prefix('ann_categories') . ' where  cid=' . $cid . '');

    [$ccid, $pid, $title] = $xoopsDB->fetchRow($requete);

    $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

    $varid[$x] = $ccid;

    $varnom[$x] = $title;

    [$nbe] = $xoopsDB->fetchRow($xoopsDB->query('select COUNT(*) FROM ' . $xoopsDB->prefix('ann_annonces') . " where valid='Yes' AND cid='$cid'"));

    $pagenav = new PageNav($nbe, $nb_affichage, $debut, "pa=view&cid=$cid&debut", '');

    if (0 != $pid) {
        $x = 1;

        while (0 != $pid) {
            $requete2 = $xoopsDB->query('select cid, pid, title from ' . $xoopsDB->prefix('ann_categories') . ' where cid=' . $pid . '');

            [$ccid, $pid, $title] = $xoopsDB->fetchRow($requete2);

            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

            $varid[$x] = $ccid;

            $varnom[$x] = $title;

            $x++;
        }

        $x -= 1;
    }

    $subcats = '';

    while (-1 != $x) {
        $subcats .= ' &raquo; <a href="index.php?pa=view&cid=' . $varid[$x] . '">' . $varnom[$x] . '</a>';

        $x -= 1;
    }

    $xoopsTpl->assign('nav_main', '<a href="index.php">' . _CLA_MAIN . '</a>');

    $xoopsTpl->assign('nav_sub', $subcats);

    $xoopsTpl->assign('nav_subcount', $nbe);

    $subresult = $xoopsDB->query('select cid, title, img from ' . $xoopsDB->prefix('ann_categories') . " where pid=$cid ORDER BY $classm");

    $numrows = $xoopsDB->getRowsNum($subresult);

    if (0 != $numrows) {
        $scount = 0;

        $xoopsTpl->assign('availability', _CLA_AVAILAB);

        while (list($ccid, $title, $img) = $xoopsDB->fetchRow($subresult)) {
            $a_cat = [];

            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

            $numrows = getTotalItems($ccid, _YES);

            $a_cat['image'] = "<img src='" . XOOPS_URL . "/modules/myads/images/cat/$img' align='absmiddle'>";

            $a_cat['link'] = '<a href="index.php?pa=view&cid=' . $ccid . "\"><b>$title</b></a>";

            $a_cat['adcount'] = $numrows;

            $a_cat['i'] = $scount;

            $a_cat['new'] = categorynewgraphic($ccid);

            $scount++;

            if (4 == $scount) {
                $scount = 0;
            }

            $xoopsTpl->append('subcategories', $a_cat);
        }

        if (0 == $count) {
            $cols = 4 - $scount;
        }

        $xoopsTpl->assign('subcat_count', $scount - 1);
    }

    showViewAnnonces($debut, $cid, $nb_affichage, $nbe);

    if (!isset($debut)) {
        $debut = 0;
    }

    //show render nav

    $xoopsTpl->assign('nav_page', $pagenav->renderNav());

    copyright();
}

/**
 *  function viewannonces
 * @param mixed $lid
 **/
function viewannonces($lid)
{
    global $xoopsDB, $xoopsConfig, $xoopsTpl, $xoopsUser, $monnaie, $claday, $ynprice, $myts, $meta, $nb_affichage;

    $GLOBALS['xoopsOption']['template_main'] = 'myads_item.html';

    // add for Nav by Tom

    require XOOPS_ROOT_PATH . '/modules/myads/class/nav.php';

    $result = $xoopsDB->query('select lid, cid, title, type, description, tel, price, typeprix, date, email, submitter, usid, town, country, valid, photo, view FROM ' . $xoopsDB->prefix('ann_annonces') . " WHERE lid = '$lid'");

    $recordexist = $xoopsDB->getRowsNum($result);

    $xoopsTpl->assign('add_from', _CLA_ADDFROM . ' ' . $xoopsConfig['sitename']);

    // Add Template assign  by Tom

    $xoopsTpl->assign('add_from_title', _CLA_ADDFROM);

    $xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);

    $xoopsTpl->assign('ad_exists', $recordexist);

    /* ---- add nav  by Tom ----- */

    $count = 0;

    $x = 0;

    $i = 0;

    $requete2 = $xoopsDB->query('select cid from ' . $xoopsDB->prefix('ann_annonces') . ' where  lid=' . $lid . '');

    [$cid] = $xoopsDB->fetchRow($requete2);

    $requete = $xoopsDB->query('select cid, pid, title from ' . $xoopsDB->prefix('ann_categories') . ' where  cid=' . $cid . '');

    [$ccid, $pid, $title] = $xoopsDB->fetchRow($requete);

    $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

    $varid[$x] = $ccid;

    $varnom[$x] = $title;

    [$nbe] = $xoopsDB->fetchRow($xoopsDB->query('select COUNT(*) FROM ' . $xoopsDB->prefix('ann_annonces') . " where valid='Yes' AND cid='$cid'"));

    if (0 != $pid) {
        $x = 1;

        while (0 != $pid) {
            $requete2 = $xoopsDB->query('select cid, pid, title from ' . $xoopsDB->prefix('ann_categories') . ' where cid=' . $pid . '');

            [$ccid, $pid, $title] = $xoopsDB->fetchRow($requete2);

            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

            $varid[$x] = $ccid;

            $varnom[$x] = $title;

            $x++;
        }

        $x -= 1;
    }

    $subcats = '';

    while (-1 != $x) {
        $subcats .= ' &raquo; <a href="index.php?pa=view&cid=' . $varid[$x] . '">' . $varnom[$x] . '</a>';

        $x -= 1;
    }

    $xoopsTpl->assign('nav_main', '<a href="index.php">' . _CLA_MAIN . '</a>');

    $xoopsTpl->assign('nav_sub', $subcats);

    $xoopsTpl->assign('nav_subcount', $nbe);

    /* ---- /nav ----- */

    if ($recordexist) {
        [$lid, $cid, $title, $type, $description, $tel, $price, $typeprix, $date, $email, $submitter, $usid, $town, $country, $valid, $photo, $view] = $xoopsDB->fetchRow($result);

        //	Specification for Japan: move after for view count up judge

        //		$xoopsDB->queryf("UPDATE ".$xoopsDB->prefix("ann_annonces")." SET view=view+1 WHERE lid = '$lid'");

        //		$useroffset = "";

        //    	if($xoopsUser)

        //    	{

        //			$timezone = $xoopsUser->timezone();

        //			if(isset($timezone))

        //				$useroffset = $xoopsUser->timezone();

        //			else

        //				$useroffset = $xoopsConfig['default_TZ'];

        //		}

        //	Specification for Japan: add  $viewcount_judge for view count up judge

        $viewcount_judge = true;

        $useroffset = '';

        if ($xoopsUser) {
            $timezone = $xoopsUser->timezone();

            if (isset($timezone)) {
                $useroffset = $xoopsUser->timezone();
            } else {
                $useroffset = $xoopsConfig['default_TZ'];
            }

            //	Specification for Japan: view count up judge

            if ((1 == $xoopsUser->getVar('uid')) || ($xoopsUser->getVar('uid') == $usid)) {
                $viewcount_judge = false;
            }
        }

        //	Specification for Japan: view count up judge

        if (true === $viewcount_judge) {
            $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('ann_annonces') . " SET view=view+1 WHERE lid = '$lid'");
        }

        $date = ($useroffset * 3600) + $date;

        $date2 = $date + ($claday * 86400);

        $date = formatTimestamp($date, 's');

        $date2 = formatTimestamp($date2, 's');

        $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

        $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

        //		$description = htmlspecialchars($description);

        $description = $myts->displayTarea($description);

        $tel = htmlspecialchars($tel, ENT_QUOTES | ENT_HTML5);

        $price = htmlspecialchars($price, ENT_QUOTES | ENT_HTML5);

        $typeprix = htmlspecialchars($typeprix, ENT_QUOTES | ENT_HTML5);

        $submitter = htmlspecialchars($submitter, ENT_QUOTES | ENT_HTML5);

        $town = htmlspecialchars($town, ENT_QUOTES | ENT_HTML5);

        $country = htmlspecialchars($country, ENT_QUOTES | ENT_HTML5);

        $imprD = "<a href=\"annonces-p-f.php?op=ImprAnn&lid=$lid\" target=_blank><img src=\"images/print.gif\" border=0 Alt=\"" . _CLA_PRINT . '" width=15 height=11></a>&nbsp;';

        $envD = "<a href=\"annonces-p-f.php?op=EnvAnn&lid=$lid\"><img src=\"images/friend.gif\" border=0 Alt=\"" . _CLA_FRIENDSEND . '" width=15 height=11></a>';

        if ($usid > 0) {
            $xoopsTpl->assign('submitter', _CLA_ANNFROM . " <a href='" . XOOPS_URL . "/userinfo.php?uid=$usid'>$submitter</a>");
        } else {
            $xoopsTpl->assign('submitter', _CLA_ANNFROM . " $submitter");
        }

        // Add PM by Tom

        //$contact_pm ="<a href=\"javascript:openWithSelfMain('".XOOPS_URL."/pmlite.php?send2=1&amp;to_userid=".$usid."', 'pmlite', 450, 380);\"><img src=\"".XOOPS_URL."/images/icons/pm.gif\" alt=\"".sprintf(_SENDPMTO,$xoopsUser->getVar('uname'))."\"></a>";

        //$xoopsTpl->assign('contact_pm', "$contact_pm");

        $xoopsTpl->assign('read', "$view " . _CLA_VIEW2);

        if ($xoopsUser) {
            $calusern = $xoopsUser->getVar('uid', 'E');

            if ($usid == $calusern) {
                $xoopsTpl->assign('modify', "<a href=\"supprann.php?op=ModAnnonce&lid=$lid\"><img src=\"images/modif.gif\" border=0 alt=\"" . _CLA_MODIFANN . "\"></a>&nbsp;<a href=\"supprann.php?op=AnnoncesDel&lid=$lid\"><img src=\"images/del.gif\" border=0 alt=\"" . _CAL_SUPPRANN . '"></a>');
            }

            if ($xoopsUser->isAdmin()) {
                $xoopsTpl->assign('admin', "<a href=\"admin/index.php?op=AnnoncesModAnnonce&lid=$lid\"><img src=\"images/modif.gif\" border=0 alt=\"" . _CLA_MODADMIN . '"></a>');
            }
        }

        $xoopsTpl->assign('type', $type);

        $xoopsTpl->assign('title', $title);

        $xoopsTpl->assign('description', $description);

        if (1 == $ynprice && $price > 0) {
            // Add Template assign  by Tom

            $xoopsTpl->assign('price', '<b>' . _CLA_PRICE2 . "</b> $price $monnaie - $typeprix");

            $xoopsTpl->assign('price_head', _CLA_PRICE2);

            $xoopsTpl->assign('price_price', "$price $monnaie");

            $xoopsTpl->assign('price_typeprix', (string)$typeprix);
        } elseif (1 == $ynprice) {
            $xoopsTpl->assign('price_head', _CLA_PRICE2);

            $xoopsTpl->assign('price_price', '');

            $xoopsTpl->assign('price_typeprix', (string)$typeprix);
        }

        $contact = '<b>' . _CLA_CONTACT . "</b> <a href=\"contact.php?lid=$lid\">" . _CLA_BYMAIL2 . '</a>';

        // Add Template assign  by Tom

        $xoopsTpl->assign('contact_head', _CLA_CONTACT);

        $xoopsTpl->assign('contact_email', "<a href=\"contact.php?lid=$lid\">" . _CLA_BYMAIL2 . '</a>');

        if ($tel) {
            $contact .= '<br><b>' . _CLA_TEL . "</b> $tel";

            // Add Template assign  by Tom

            $xoopsTpl->assign('contact_tel_head', _CLA_TEL);

            $xoopsTpl->assign('contact_tel', (string)$tel);
        }

        // Layout CHG by Tom

        $contact .= '<br>';

        if ($town) {
            $contact .= '<br><b>' . _CLA_TOWN . "</b> $town";

            // Add Template assign  by Tom

            $xoopsTpl->assign('local_town', (string)$town);
        }

        if ($country) {
            $contact .= '<br><b>' . _CLA_COUNTRY . "</b> $country";

            // Add Template assign  by Tom

            $xoopsTpl->assign('local_country', (string)$country);
        }

        $xoopsTpl->assign('contact', $contact);

        // Add Template assign  by Tom

        $xoopsTpl->assign('local_head', _CLA_LOCAL);

        if ($photo) {
            // add 'alt=' by Tom

            $xoopsTpl->assign('photo', "<img src=\"images_ann/$photo\" alt=\"$title\">");
        }

        $xoopsTpl->assign('date', _CLA_DATE2 . " $date " . _CLA_DISPO . " $date2 &nbsp;&nbsp; $imprD $envD");
    } else {
        $xoopsTpl->assign('no_ad', _CLA_NOCLAS);
    }

    $result8 = $xoopsDB->query('select title from ' . $xoopsDB->prefix('ann_categories') . " where cid=$cid");

    [$ctitle] = $xoopsDB->fetchRow($result8);

    $xoopsTpl->assign('link_main', '<a href="../myads/">' . _CLA_MAIN . '</a>');

    $xoopsTpl->assign('link_cat', "<a href=\"index.php?pa=view&cid=$cid\">" . _CLA_GORUB . " $ctitle</a>");

    copyright();
}

/**
 *  function categorynewgraphic
 * @param mixed $cat
 *
 * @return string
 * @return string
 */
function categorynewgraphic($cat)
{
    global $xoopsDB;

    $newresult = $xoopsDB->query('select date from ' . $xoopsDB->prefix('ann_annonces') . " where cid=$cat and valid = 'Yes' order by date desc limit 1");

    [$timeann] = $xoopsDB->fetchRow($newresult);

    $count = 1;

    $startdate = (time() - (86400 * $count));

    if ($startdate < $timeann) {
        return '<img src="' . XOOPS_URL . '/modules/myads/images/newred.gif">';
    }
}

######################################################

$pa = $_GET['pa'] ?? '';
$lid = $_GET['lid'] ?? '';
$cid = $_GET['cid'] ?? '';
$debut = $_GET['debut'] ?? '';

/*
if (!isset($pa))
    $pa = '';
if (!isset($debut))
    $debut = '';
*/
switch ($pa) {
    case 'viewannonces':
        viewannonces($lid);
        break;
    case 'view':
        view($cid, $debut);
        break;
    //    case "views":
    //    views($sid, $debut);
    //    break;

    default:
        index();
        break;
}

require XOOPS_ROOT_PATH . '/footer.php';
