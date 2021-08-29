<?php 
include '../dbcommon/database.php';
$db = new Database();
$skey = '2021_HatidAppApplication';

parse_str(file_get_contents("php://input"),$req);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;      
require 'autoload.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';


if(isset($req['registerParcel'])){
    if($req['sKey'] == $skey){
        $description = $req['description'];
        $quality = $req['quality'];
        $pickLocation = $req['pickLocation'];
        $address1 = $req['address1'];
        $receiverName = $req['receiverName'];
        $address2 = $req['address2'];
        $receiverContact = $req['receiverContact'];
        $paymentMethod = $req['paymentMethod'];
        $fee = $req['fee'];
        $category = $req['category'];
        $user_id = $req['userId'];
        $dropLocation = $req['dropLocation'];
        $sender_email = $req['email'];
        $lat_sender = $req['lat1'];
        $long_sender = $req['long1'];
        $lat_receiver = $req['lat2'];
        $long_receiver = $req['long2'];
        $distance = $req['range'];

        $fields = "(description,product_quality,pickup_location,staddress,receiver_name,receiver_contact,drop_location,staddress2,payment_method,
        total_fee,category,sender_id,on_deliver,create_date,verified_by_email,sender_lat,sender_long,receiver_lat,receiver_long,arrive_cat,distance)";
	    
		
        $sql = $db->Connection()->prepare("INSERT INTO hatid_parcel_info {$fields}
	   VALUES('{$description}','{$quality}','{$pickLocation}','{$address1}','{$receiverName}','{$receiverContact}','{$dropLocation}',
        '{$address2}','{$paymentMethod}','{$fee}','{$category}','{$user_id}','0', NOW(),'0','{$lat_sender}','{$long_sender}'
        ,'{$lat_receiver}','{$long_receiver}','0','{$distance}')");
		
        if($sql->execute()){
            $sql = $db->Connection()->query("SELECT id FROM hatid_parcel_info ORDER by id DESC LIMIT 1");
            $result = $sql->fetchAll(PDO::FETCH_OBJ);
            foreach ($result as $key => $data) {
               $id = $data->id;
            }

            if($paymentMethod == '0'){
                $fields = "(rider_id,sender_id,payment,delivery_fee,parcel_id,hatid_payment.range,payment_type,verify_payment)";
                $q = $db->Connection()->prepare("INSERT INTO hatid_payment {$fields}
                VALUES('0','{$user_id}','{$fee}'
                ,'{$fee}','{$id}','{$distance}','{$paymentMethod}','0')");
                $q->execute();
            }

          
            $otp = rand(1000,50000);
            $email = $sender_email;
            $res = mailer($email,$otp);
            if ($res == "0"){
                 print(json_encode(array($otp,$id)));
            }else{
                 print(json_encode(array("1")));
            }

        }else{
            print(json_encode(array("1")));
        }
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