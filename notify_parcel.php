<?php 

include 'dbcommon/database.php';
$db = new Database();
$skey = '2021_HatidAppApplication';

parse_str(file_get_contents("php://input"),$req);

if(isset($req['arrive_parcel'])){
    if($req['sKey'] == $skey){
        $notif_type = $req['notif_type'];
        $parcel_id = $req['parcel_id'];
        $rider_id = $req['rider_id'];
        $read_flg = $req['read_flg'];
        $sender_id = $req['senderId'];
        
        $fields = "(notif_type,create_date,rider_id,parcel_id,read_flg,sender_id)";
        $sql = $db->Connection()->prepare("INSERT INTO notification_list {$fields}
        VALUES('{$notif_type}', NOW(),'{$rider_id}','{$parcel_id}','{$read_flg}','{$sender_id}')");

        if($sql->execute()){

            $query = $db->Connection()->prepare("UPDATE hatid_parcel_info SET arrive_cat = '{$notif_type}'
            WHERE id = '{$parcel_id}'");

            if($query->execute()){
                print(json_encode(array("0")));
            }else{
                print(json_encode(array("1")));
            }
        }else{
            print(json_encode(array("1")));
        }
    }
}




if(isset($req['notif_parcel'])){
    if($req['sKey'] == $skey){
        $parcel_id = $req['parcel_id'];
        $rider_id = $req['rider_id'];
        
        $sql = $db->Connection()->query("SELECT notif_type 
        FROM notification_list WHERE rider_id = '{$rider_id}'
        AND parcel_id = '{$parcel_id}'");

        $sql->execute();

        $result = $sql->fetchAll(PDO::FETCH_ASSOC);

        if(!empty($result)){
            print(json_encode($result));
        }else{
            print(json_encode(array("empty")));
        }
    }
}
?>