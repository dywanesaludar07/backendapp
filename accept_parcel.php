<?php 

include 'dbcommon/database.php';
$db = new Database();
$skey = '2021_HatidAppApplication';
error_reporting(0);

parse_str(file_get_contents("php://input"),$req);

if(isset($req['accept_parcel'])){
    if($req['sKey'] == $skey){
        $rider_id = $req['rider_id'];
        $parcel_id = $req['parcel_id'];

        $sql = $db->Connection()->prepare("UPDATE hatid_parcel_info
        SET on_deliver = '{$rider_id}' WHERE id = '{$parcel_id}'");
        if($sql->execute()){

            $query = $db->Connection()->prepare("UPDATE hatid_payment SET rider_id = '{$rider_id}'
            WHERE parcel_id = '{$parcel_id}'");
            $query->execute();

            print(json_encode(array("0")));
        }else{
            print(json_encode(array("1")));
        }
    }
}
?>