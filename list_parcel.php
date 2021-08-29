
<?php 

include 'dbcommon/database.php';
$db = new Database();
$skey = '2021_HatidAppApplication';
error_reporting(0);

parse_str(file_get_contents("php://input"),$req);
if(isset($req['list_parcel'])){
    if($req['sKey'] == $skey){
        $user_id = $req['userId'];
        $sql = $db->Connection()->query("SELECT * FROM hatid_parcel_info
        WHERE on_deliver != 0 AND sender_id = '{$user_id}' AND verified_by_email = 1
        AND id NOT IN (SELECT parcel_id FROM payment_history WHERE rider_id = on_deliver)");

        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);

        if(!empty($result)){
            print(json_encode($result));
        }else{
            print(json_encode(array("1")));
        }
    }
}
else if(isset($req['pending_parcel'])){
    if($req['sKey'] == $skey){
        if(isset($req['userId'])){
            $user_id = $req['userId'];
            $and = "AND sender_id = '{$user_id}'";
        }
        $sql = $db->Connection()->query("SELECT *,(SELECT account_name FROM account_table WHERE id = sender_id) as account_name
        FROM hatid_parcel_info
        WHERE on_deliver = 0 {$and} AND verified_by_email = 1");
  
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
  
        if(!empty($result)){
            print(json_encode($result));
        }else{
            print(json_encode(array("1")));
        }
    }
}

else if(isset($req['unverified_parcel'])){
    if($req['sKey'] == $skey){
        $user_id = $req['userId'];
        $sql = $db->Connection()->query("SELECT * FROM hatid_parcel_info
        WHERE on_deliver = 0 AND sender_id = '{$user_id}' AND verified_by_email = 0");

        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);

        if(!empty($result)){
            print(json_encode($result));
        }else{
            print(json_encode(array("1")));
        }
    }
}
else if (isset($req['cancel_pending'])){
    if($req['sKey'] == $skey){
        $user_id = $req['userId'];
        $parcel_id = $req['parcelId'];

        $sql = $db->Connection()->prepare("DELETE FROM hatid_parcel_info
        WHERE id = '{$parcel_id}' AND sender_id = '{$user_id}'");

        if($sql->execute()){
            print(json_encode(array("0")));
        }else{
            print(json_encode(array("1")));
        }
    }
}
?>