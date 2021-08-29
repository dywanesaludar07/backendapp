<?php
  include '../dbcommon/database.php';
  parse_str(file_get_contents("php://input"),$req);
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;      
  require 'autoload.php';
  require 'phpmailer/src/Exception.php';
  require 'phpmailer/src/PHPMailer.php';
  require 'phpmailer/src/SMTP.php';


  /***REQUESTED EMAIL */
  $db = new Database();
  $email = $req['email'];

  $sql = $db->Connection()->query("SELECT id FROM account_table
  WHERE email_address = '{$email}' AND delete_flg = 0");
  $sql->execute();
  $result = $sql->fetchAll(PDO::FETCH_OBJ);

  if(!empty($result)){
     print(json_encode(array("3")));
  }else{
    
    $otp = rand(999,50000);
    $res = mailer($email,$otp);
  
    if ($res == "0"){
      print(json_encode(array($otp)));
    }else{
      print(json_encode(array("1")));
    }
  }

  function mailer($email,$otp){
    set_time_limit(500);
    $mail = new PHPMailer(true);

    try {
      $mail->IsSMTP();
      $mail->SMTPDebug = 0;
      $mail->SMTPAuth = TRUE;
      $mail->SMTPSecure = "SSL";
      $mail->Port     = 587;  
      $mail->Username = 'ehatidcourierservices@gmail.com';
      $mail->Password = "Ehatidcourier2021";
      $mail->Host = 'Smtp.gmail.com';
      $mail->Mailer   = "smtp";
      $mail->SMTPOptions = array(
          'ssl' => array(
              'verify_peer' => false,
              'verify_peer_name' => false,
              'allow_self_signed' => true
          )
      );
      $mail->SetFrom('ehatidcourierservices@gmail.com', "<no-reply>");
      $mail->AddReplyTo('ehatidcourierservices@gmail.com', "PHPPot");
      
      $mail->AddAddress($email);
      $mail->Subject = 'OTP Verification Code';
      $mail->WordWrap   = 80;
      $content = "Welcome to E-hatid Application. Your OTP is ".$otp." Please do not share this to others."; 
      $mail->MsgHTML($content);
      $mail->IsHTML(true);
      
      if($mail->Send()){
          return "0";
      }else{
          return "1";
      }
    } catch (Exception $e) {
        return json_encode(array("invalid"));
    }
  }
?>