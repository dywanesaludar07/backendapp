<?php 

include 'dbcommon/database.php';
$db = new Database();
$skey = '2021_HatidAppApplication';
error_reporting(0);
parse_str(file_get_contents("php://input"),$req);


if(isset($req['get_history'])){
    if($req['sKey'] == $skey){
        $rider_id = $req['rider_id'];

        $sql = $db->Connection()->query("SELECT payment_history.rider_id,payment_history.delivery_fee,
        payment_history.created_date,payment_history.parcel_id,payment_history.distance,
        hatid_payment.payment_type,hatid_payment.id
        FROM payment_history INNER JOIN hatid_payment ON payment_history.parcel_id = hatid_payment.parcel_id 
        WHERE hatid_payment.rider_id = '{$rider_id}' AND DATE(payment_history.`created_date`) = DATE(NOW())");

        $result = $sql->fetchAll(PDO::FETCH_OBJ);
        
        if(!empty($result)){
            print(json_encode($result));
        }else{
            print(json_encode(array("0")));
        }
    }   
}

if(isset($req['get_recent'])){
    if($req['sKey'] == $skey){
        $rider_id = $req['rider_id'];

        $sql = $db->Connection()->query("SELECT payment_history.rider_id,payment_history.delivery_fee,
        payment_history.created_date,payment_history.parcel_id,payment_history.distance,
        hatid_payment.payment_type,hatid_payment.id
        FROM payment_history INNER JOIN hatid_payment ON payment_history.parcel_id = hatid_payment.parcel_id 
        WHERE hatid_payment.rider_id = '{$rider_id}' AND DATE(payment_history.`created_date`) != DATE(NOW()) ");

        $result = $sql->fetchAll(PDO::FETCH_OBJ);
        
        if(!empty($result)){
            print(json_encode($result));
        }else{
            print(json_encode(array("0")));
        }
    }   
}

if(isset($req['get_details'])){
    if($req['sKey'] == $skey){
        $rider_id = $req['rider_id'];
        $parcelId = $req['id'];

        $sql = $db->Connection()->query("SELECT sender_id,id,payment,parcel_id, hatid_payment.range,
        (SELECT payment_method FROM hatid_parcel_info WHERE sender_id = sender_id AND id = parcel_id) AS payment_method, 
        (SELECT account_name FROM account_table WHERE id = sender_id) AS account_name,
        (SELECT pickup_location FROM hatid_parcel_info WHERE sender_id = sender_id AND id = parcel_id) AS sender_location, 
        (SELECT receiver_name FROM hatid_parcel_info WHERE sender_id = sender_id AND id = parcel_id) AS receiver_name, 
        (SELECT drop_location FROM hatid_parcel_info WHERE sender_id = sender_id AND id = parcel_id) AS receiver_location
        FROM hatid_payment
        WHERE rider_id = '{$rider_id}' AND id = '{$parcelId}'");

        $result = $sql->fetchAll(PDO::FETCH_OBJ);
        
        if(!empty($result)){
            print(json_encode($result));
        }else{
            print(json_encode(array("0")));
        }
    }   
}



?>