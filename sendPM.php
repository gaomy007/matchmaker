<?php
require 'phpmail/PHPMailerAutoload.php';
require_once("models/config.php");
$loggedInUser = $_SESSION["userCakeUser"];
$userid = $loggedInUser->user_id;
// initiate a new pm class
$pm = new cpm($userid);

$namefrom = $_POST['namefrom'];
$nameto = $_POST['nameto'];
$emailfrom = $_POST['emailfrom'];
$emailto = $_POST['emailto'];
$message = $_POST['message'];
$subject = $_POST['subject'];


$pmr=$pm->sendmessage($nameto,$subject,$message);

//Create a new PHPMailer instance
$mail = new PHPMailer;

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;

//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';

//Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;

//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';

//Whether to use SMTP authentication
$mail->SMTPAuth = true;

//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = "gaomy1202@gmail.com";

//Password to use for SMTP authentication
$mail->Password = "Aarose007";

//Set who the message is to be sent from
$mail->setFrom("donotreply@fatematch.com", "donotreply");

//Set an alternative reply-to address
$mail->addReplyTo("donotreply@fatematch.com", "donotreply");

//Set who the message is to be sent to
$mail->addAddress($emailto, "$nameto");

//Set the subject line
$mail->Subject = "You get an Message from a Mutual Match user";

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML($message);


$body = "Hi $nameto,<br>you have a new message on Match Maker. Please check it out on our website!";
//Replace the plain text body with one created manually
$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
$mail->MsgHTML($body);
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors


if (!$mail->send()&&$pmr) {
  
    echo "Message Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}


?>
