<?php 

include 'dbcommon/database.php';
$db = new Database();
$skey = '2021_HatidAppApplication';

parse_str(file_get_contents("php://input"),$req);
if(isset($req['checkPayment'])){
    if($skey == $req['sKey']){
        $parcel_id = $req['parcel_id'];
        $rider_id = $req['rider_id'];

        $sql = $db->Connection()->query("SELECT payment_type, (SELECT payment_method FROM hatid_parcel_info WHERE id = '{$parcel_id}')
        as payment_method
        FROM hatid_payment
        WHERE rider_id = '{$rider_id}' AND parcel_id = '{$parcel_id}' AND verify_payment= 0");

        $result = $sql->fetchAll(PDO::FETCH_ASSOC);

        if(!empty($result)){
            print(json_encode($result));
        }else{
            print(json_encode(array("err_no")));
        }
    }
}
if(isset($req['accept_payment'])){
    if($skey == $req['sKey']){
        $parcel_id = $req['parcelId'];
        $rider_id = $req['rider_id'];

        $sql = $db->Connection()->prepare("UPDATE hatid_payment SET verify_payment = 1
        WHERE parcel_id = '{$parcel_id}' AND rider_id = '{$rider_id}'");

        if($sql->execute()){
            print(json_encode(array("0")));
        }else{
            print(json_encode(array("1")));
        }
    }
}
?>