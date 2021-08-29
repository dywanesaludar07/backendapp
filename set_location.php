<?php 

include 'dbcommon/database.php';
$db = new Database();
$skey = '2021_HatidAppApplication';
error_reporting(0);

parse_str(file_get_contents("php://input"),$req);

if(isset($req['set_location'])){
    if($req['sKey'] == $skey){
        $rider_id = $req['rider_id'];
        $latitude = $req['latitude'];
        $longitude = $req['longitude'];
        $parcel_id = $req['parcel_id'];

        $sql = $db->Connection()->query("SELECT id FROM rider_location WHERE parcel_id = '{$parcel_id}'");
        
        if($sql->rowCount() > 0){
            $sql = $db->Connection()->prepare("UPDATE rider_location SET lat_rider = '{$latitude}',
            long_rider = '{$longitude}'");
            if($sql->execute()){
                print(json_encode(array("0")));
            }else{
                print(json_encode(array("1")));
            }
        }else{
            $sql = $db->Connection()->prepare("INSERT INTO rider_location (rider_id,lat_rider,long_rider,parcel_id)
            VALUES ('{$rider_id}','{$latitude}','{$longitude}','{$parcel_id}')");
            if($sql->execute()){
                print(json_encode(array("0")));
            }else{
                print(json_encode(array("1")));
            }
        }
    }
}


if(isset($req['get_location'])){
    if($req['sKey'] == $skey){
       $parcel_id = $req['parcel_id'];

       $sql = $db->Connection()->query("SELECT lat_rider,long_rider 
       FROM rider_location
       WHERE parcel_id = '{$parcel_id}'");

        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            print(json_encode($result));
        }else{
            print(json_encode(array("1")));
        }
    }
}
?>