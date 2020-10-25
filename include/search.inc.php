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
function ann_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;

    $sql = 'select lid,usid,title,date FROM ' . $xoopsDB->prefix('ann_annonces') . " WHERE valid='Yes' AND date<=" . time() . '';

    if (0 != $userid) {
        $sql .= ' AND usid=' . $userid . ' ';
    }

    // because count() returns 1 even if a supplied variable

    // is not an array, we must check if $querryarray is really an array

    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((description LIKE '%$queryarray[0]%' OR title LIKE '%$queryarray[0]%')";

        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";

            $sql .= "(description LIKE '%$queryarray[$i]%' OR title LIKE '%$queryarray[$i]%')";
        }

        $sql .= ') ';
    }

    $sql .= 'ORDER BY date DESC';

    $result = $xoopsDB->query($sql, $limit, $offset);

    $ret = [];

    $i = 0;

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $ret[$i]['image'] = 'images/cat/default.gif';

        $ret[$i]['link'] = 'index.php?pa=viewannonces&lid=' . $myrow['lid'] . '';

        $ret[$i]['title'] = $myrow['title'];

        $ret[$i]['time'] = $myrow['date'];

        $ret[$i]['uid'] = $myrow['usid'];

        $i++;
    }

    return $ret;
}
