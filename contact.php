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

foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

if ($submit) {
    include 'header.php';

    global $xoopsConfig, $xoopsDB, $myts, $meta;

    $result = $xoopsDB->query('select email, submitter, title, type, description FROM  ' . $xoopsDB->prefix('ann_annonces') . " WHERE lid = '$id'");

    while (list($email, $submitter, $title, $type, $description) = $xoopsDB->fetchRow($result)) {
        $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

        $type = htmlspecialchars($type, ENT_QUOTES | ENT_HTML5);

        $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5);

        $submitter = htmlspecialchars($submitter, ENT_QUOTES | ENT_HTML5);

        if ($tele) {
            $teles = '' . _CLA_ORAT . " $tele";
        } else {
            $teles = '';
        }

        // Specification for Japan:

        // $message .= ""._CLA_MESSFROM." $namep "._CLA_FROMANNOF." ".$meta['title']."\n\n";

        // $message .= ""._CLA_REMINDANN."\n$type : $titre\nTexte : $description\n\n";

        // $message .= "--------------- "._CLA_STARTMESS." $namep -------------------\n";

        // $message .= "$messtext\n\n";

        // $message .= "--------------- "._CLA_ENDMESS." de $namep -------------------\n\n";

        // $message .= ""._CLA_CANJOINT." $namep "._CLA_TO." $post $teles";

        $message = '' . _CLA_MESSFROM . " $namep   $post   " . $meta['title'] . "\n\n";

        $message .= '' . _CLA_REMINDANN . " $type : $title\n" . _CLA_MESSAGETEXT . " : $description\n\n";

        $message .= '--------------- ' . _CLA_STARTMESS . " $namep " . _CLA_FROMANNOF . "-------------------\n";

        $message .= "$messtext\n\n";

        $message .= '--------------- ' . _CLA_ENDMESS . " -------------------\n\n";

        $message .= '' . _CLA_CANJOINT . " $namep " . _CLA_TO . " $post $teles \n\n";

        $message .= "End of message \n\n";

        $subject = '' . _CLA_CONTACTAFTERANN . '';

        $mail = getMailer();

        $mail->useMail();

        //$mail->setFromName($meta['title']);

        $mail->setFromEmail($post);

        $mail->setToEmails($email);

        $mail->setSubject($subject);

        $mail->setBody($message);

        $mail->send();

        echo $mail->getErrors();
    }

    redirect_header('index.php', 1, _CLA_MESSEND);

    exit();
}
    $lid = $_GET['lid'] ?? '';

    include 'header.php';
    require XOOPS_ROOT_PATH . '/header.php';
    OpenTable();

    echo '<script>
          function verify() {
                var msg = "' . _CLA_VALIDERORMSG . '\\n__________________________________________________\\n\\n";
                var errors = "FALSE";

			
				if (document.Cont.namep.value == "") {
                        errors = "TRUE";
                        msg += "' . _CLA_VALIDSUBMITTER . '\\n";
                }
				
				if (document.Cont.post.value == "") {
                        errors = "TRUE";
                        msg += "' . _CLA_VALIDEMAIL . '\\n";
                }
				
				if (document.Cont.messtext.value == "") {
                        errors = "TRUE";
                        msg += "' . _CLA_VALIDMESS . '\\n";
                }
				
  
                if (errors == "TRUE") {
                        msg += "__________________________________________________\\n\\n' . _CLA_VALIDMSG . '\\n";
                        alert(msg);
                        return false;
                }
          }
          </script>';

    echo '<B>' . _CLA_CONTACTAUTOR . '</B><br><br>';
    echo '' . _CLA_TEXTAUTO . '<br>';
    echo '<form onSubmit="return verify();" method="post" action="contact.php" NAME="Cont">';
    echo "<INPUT TYPE=\"hidden\" NAME=\"id\" VALUE=\"$lid\">";
    echo '<INPUT TYPE="hidden" NAME="submit" VALUE="1">';

    if ($xoopsUser) {
        $idd = $xoopsUser->getVar('name', 'E');

        $idde = $xoopsUser->getVar('email', 'E');
    }

    echo "<TABLE width='100%' class='outer' cellspacing='1'>
    <TR>
      <TD class='head'>" . _CLA_YOURNAME . "</TD>
      <TD class='even'><input type=\"text\" name=\"namep\" size=\"42\" value=\"$idd\"></TD>
    </TR>
    <TR>
      <TD class='head'>" . _CLA_YOUREMAIL . "</TD>
      <TD class='even'><input type=\"text\" name=\"post\" size=\"42\" value=\"$idde\"></font></TD>
    </TR>
    <TR>
      <TD class='head'>" . _CLA_YOURPHONE . "</TD>
      <TD class='even'><input type=\"text\" name=\"tele\" size=\"42\"></font></TD>
    </TR>
    <TR>
      <TD class='head'>" . _CLA_YOURMESSAGE . "</TD>
      <TD class='even'><textarea rows=\"5\" name=\"messtext\" cols=\"40\"></textarea></TD>
    </TR>
	</TABLE><br>
      <p><INPUT TYPE=\"submit\" VALUE=\"" . _CLA_SENDFR . '">
	</form>';

    CloseTable();
    require XOOPS_ROOT_PATH . '/footer.php';
