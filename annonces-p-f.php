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

function EnvAnn($lid)
{
    global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsTheme, $xoopsLogger;

    $result = $xoopsDB->query('select lid, title, type FROM ' . $xoopsDB->prefix('ann_annonces') . " where lid=$lid");

    [$lid, $title, $type] = $xoopsDB->fetchRow($result);

    OpenTable();

    echo '
	    <b>' . _CLA_SENDTO . " $lid \"<B>$type : $title</B>\" " . _CLA_FRIEND . "<br><br>
	    <form action=\"annonces-p-f.php\" method=post>
	    <input type=hidden name=lid value=$lid>";

    if ($xoopsUser) {
        $idd = $iddds = $xoopsUser->getVar('name', 'E');

        $idde = $iddds = $xoopsUser->getVar('email', 'E');
    }

    echo "
	<TABLE width='100%' class='outer' cellspacing='1'>
    <TR>
      <TD class='head' width='30%'>" . _CLA_NAME . " </TD>
      <TD class='even'><input class=textbox type=text name=\"yname\" value=\"$idd\"></TD>
    </TR>
    <TR>
      <TD class='head'>" . _CLA_MAIL . " </TD>
      <TD class='even'><input class=textbox type=text name=\"ymail\" value=\"$idde\"></TD>
    </TR>
    <TR>
      <TD COLSPAN=2 class='even'>&nbsp;</TD>
    </TR>
    <TR>
      <TD class='head'>" . _CLA_NAMEFR . " </TD>
      <TD class='even'><input class=textbox type=text name=\"fname\"></TD>
    </TR>
    <TR>
      <TD class='head'>" . _CLA_MAILFR . " </TD>
      <TD class='even'><input class=textbox type=text name=\"fmail\"></TD>
    </TR>
	</TABLE><br>
    <input type=hidden name=op value=MailAnn>
    <input type=submit value=" . _CLA_SENDFR . '>
    </form>     ';

    CloseTable();

    //	Copyright();
    //	require XOOPS_ROOT_PATH."/footer.php";
}

function MailAnn($lid, $yname, $ymail, $fname, $fmail)
{
    global $xoopsConfig, $xoopsUser, $xoopsDB, $monnaie, $ynprice, $myts, $xoopsLogger;

    $result = $xoopsDB->query('select lid, title, type, description, tel, price, typeprix, date, email, submitter, town, country, photo FROM ' . $xoopsDB->prefix('ann_annonces') . " where lid=$lid");

    [$lid, $title, $type, $description, $tel, $price, $typeprix, $date, $email, $submitter, $town, $country, $photo] = $xoopsDB->fetchRow($result);

    $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

    $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

    $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5);

    //	$description = $myts->displayTarea($description);

    $tel = htmlspecialchars($tel, ENT_QUOTES | ENT_HTML5);

    $price = htmlspecialchars($price, ENT_QUOTES | ENT_HTML5);

    $typeprix = htmlspecialchars($typeprix, ENT_QUOTES | ENT_HTML5);

    $submitter = htmlspecialchars($submitter, ENT_QUOTES | ENT_HTML5);

    $town = htmlspecialchars($town, ENT_QUOTES | ENT_HTML5);

    $country = htmlspecialchars($country, ENT_QUOTES | ENT_HTML5);

    //	Specification for Japan:

    //	$message .= ""._CLA_HELLO." $fname,\n\n$yname "._CLA_MESSAGE."\n\n";

    $subject = '' . _CLA_SUBJET . ' ' . $xoopsConfig['sitename'] . '';

    $message = " $fname" . _CLA_HELLO . ",\n\n$yname " . _CLA_MESSAGE . "\n\n";

    $message .= "$type :  $title\n$description\n\n";

    if ($price && 1 == $ynprice) {
        $message .= '' . _CLA_PRICE2 . " $price $monnaie $typeprix\n";
    }

    $message .= '' . _CLA_BYMAIL . ' ' . XOOPS_URL . "/modules/myads/contact.php?lid=$lid\n";

    if ($tel) {
        $message .= '' . _CLA_TEL2 . " $tel\n";
    }

    if ($town) {
        $message .= '' . _CLA_TOWN . " $town\n";
    }

    if ($country) {
        $message .= '' . _CLA_COUNTRY . " $country\n";
    }

    $message .= "\n" . _CLA_INTERESS . ' ' . $xoopsConfig['sitename'] . "\n" . XOOPS_URL . '/modules/myads/';

    //    mail($fmail, $subject, $message, "From: \"$yname\" <$ymail>\nX-Mailer: PHP/" . phpversion());

    $mail = getMailer();

    $mail->useMail();

    $mail->setFromEmail($ymail);

    $mail->setToEmails($fmail);

    $mail->setSubject($subject);

    $mail->setBody($message);

    $mail->send();

    echo $mail->getErrors();

    redirect_header('index.php', 1, _CLA_ANNSEND);

    exit();
}

function ImprAnn($lid)
{
    //global $xoopsConfig, $xoopsDB, $monnaie, $useroffset, $claday, $ynprice, $myts,$xoopsLogger;

    global $xoopsConfig, $xoopsUser, $xoopsDB, $monnaie, $useroffset, $claday, $ynprice, $myts, $xoopsLogger;

    $currenttheme = getTheme();

    $result = $xoopsDB->query('select lid, title, type, description, tel, price, typeprix, date, email, submitter, town, country, photo FROM ' . $xoopsDB->prefix('ann_annonces') . " where lid=$lid");

    [$lid, $title, $type, $description, $tel, $price, $typeprix, $date, $email, $submitter, $town, $country, $photo] = $xoopsDB->fetchRow($result);

    $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

    $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

    //	$description = htmlspecialchars($description);

    $description = $myts->displayTarea($description);

    $tel = htmlspecialchars($tel, ENT_QUOTES | ENT_HTML5);

    $price = htmlspecialchars($price, ENT_QUOTES | ENT_HTML5);

    $typeprix = htmlspecialchars($typeprix, ENT_QUOTES | ENT_HTML5);

    $submitter = htmlspecialchars($submitter, ENT_QUOTES | ENT_HTML5);

    $town = htmlspecialchars($town, ENT_QUOTES | ENT_HTML5);

    $country = htmlspecialchars($country, ENT_QUOTES | ENT_HTML5);

    echo '
    <html>
    <head><title>' . $xoopsConfig['sitename'] . '</title>
	<LINK REL="StyleSheet" HREF="../../themes/' . $currenttheme . '/style/style.css" TYPE="text/css">
	</head>
    <body bgcolor="#FFFFFF" text="#000000">
    <table border=0><tr><td>
    
    <table border=0 width=640 cellpadding=0 cellspacing=1 bgcolor="#000000"><tr><td>
    <table border=0 width=100% cellpadding=15 cellspacing=1 bgcolor="#FFFFFF"><tr><td>';

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

    $date2 = $date + ($claday * 86400);

    $date = formatTimestamp($date, 's');

    $date2 = formatTimestamp($date2, 's');

    echo '<br><br><TABLE WIDTH=100% BORDER=0>
	    <TR>
      <TD>' . _CLA_ANNFROM . " $submitter (No. $lid )<br><br>";

    echo " <b>$type :</b> <I>$title</I> ";

    echo "</TD>
	      </TR>
    <TR>
      <TD><DIV STYLE=\"text-align:justify;\">$description</DIV><P>";

    if ($price && 1 == $ynprice) {
        echo '<B>' . _CLA_PRICE2 . "</B> $price $monnaie - $typeprix<br>";
    }

    echo '' . _CLA_BYMAIL . ' <A HREF="' . XOOPS_URL . "/modules/myads/contact.php?lid=$lid\">" . XOOPS_URL . "/modules/myads/contact.php?lid=$lid</A>";

    if ($tel) {
        echo '<br>' . _CLA_TEL . " $tel";
    }

    if ($town) {
        echo '<br>' . _CLA_TOWN . " $town";
    }

    if ($country) {
        echo '<br>' . _CLA_COUNTRY . " $country";
    }

    echo '<br><br>' . _CLA_DATE2 . " $date " . _CLA_DISPO . " $date2<br><br>";

    if ($photo) {
        echo "<CENTER><IMG SRC=\"images_ann/$photo\" BORDER=0></CENTER>";
    }

    echo '</TD>
	</TR>
	</TABLE>';

    echo '<br><br></td></tr></table></td></tr></table>
    <br><br><center>
    ' . _CLA_EXTRANN . ' <B>' . $xoopsConfig['sitename'] . '</B><br>
    <a href="' . XOOPS_URL . '/modules/myads/">' . XOOPS_URL . '/modules/myads/</a>
    </td></tr></table>
    </body>
    </html>';
}

##############################################################
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

$lid = $_GET['lid'] ?? '';

if (!isset($_POST['op']) && isset($_GET['op'])) {
    $op = $_GET['op'];
}

switch ($op) {
    case 'EnvAnn':
        require XOOPS_ROOT_PATH . '/header.php';
        EnvAnn($lid);
        require XOOPS_ROOT_PATH . '/footer.php';
        break;
    case 'MailAnn':
        MailAnn($lid, $yname, $ymail, $fname, $fmail);
        break;
    case 'ImprAnn':
        ImprAnn($lid);
        break;
    default:
        redirect_header('index.php', 1, '' . _RETURNGLO . '');
        break;
}
