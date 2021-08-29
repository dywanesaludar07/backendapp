<?php
include('PHPMailer.php');
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug = 0;
$mail->SMTPAuth = TRUE;
$mail->SMTPSecure = "tls";
$mail->Port     = 587;  
$mail->Username = "your gmail username";
$mail->Password = "your gmail password";
$mail->Host     = "smtp.gmail.com";
$mail->Mailer   = "smtp";
$mail->SetFrom("Your from email", "from name");
$mail->AddReplyTo("from email", "PHPPot");
$mail->AddAddress("recipient email");
$mail->Subject = "Test email using PHP mailer";
$mail->WordWrap   = 80;
$content = "<b>This is a test email using PHP mailer class.</b>"; $mail->MsgHTML($content);
$mail->IsHTML(true);
if(!$mail->Send()) 
echo "Problem sending email.";
else 
echo "email sent.";
?>