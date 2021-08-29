<?php 

include 'dbcommon/database.php';
$db = new Database();
$skey = '2021_HatidAppApplication';
error_reporting(0);

parse_str(file_get_contents("php://input"),$req);

if(isset($req['parcel_details'])){
    if($req['sKey'] == $skey){
        if(isset($req['arrive_cat'])){
            $and = "AND arrive_cat != 4";
        }
        $parcelId = $req['parcelId'];
        $sql = $db->Connection()->query("SELECT *,(SELECT account_name FROM account_table WHERE id = sender_id) as account_name, 
        (SELECT contact_number FROM account_table WHERE id = sender_id) as contact_number
        FROM hatid_parcel_info 
        WHERE id = '{$parcelId}'");

        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        if($sql->execute()){
            print(json_encode($result));
        }else{
            print(json_encode(array("1")));
        }
    }
}


if(isset($req['parcel_on_going'])){
    if($req['sKey'] == $skey){
        $rider_id = $req['rider_id'];
        $sql = $db->Connection()->query("SELECT *,(SELECT account_name FROM account_table WHERE id = sender_id) as account_name, 
        (SELECT contact_number FROM account_table WHERE id = sender_id) as contact_number,
        IF (id NOT IN (SELECT parcel_id FROM hatid_payment WHERE rider_id = '{$rider_id}' AND verify_payment = '1'), '0','1') AS pay_cat
        FROM hatid_parcel_info 
        WHERE on_deliver = '{$rider_id}' AND arrive_cat != '3'");

        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            print(json_encode($result));
        }else{
            print(json_encode(array("1")));
        }
    }
}

if(isset($req['to_pay'])){
    if($req['sKey'] == $skey){
        $userId = $req['userId'];
        $sql = $db->Connection()->query("SELECT * FROM hatid_parcel_info 
        WHERE id NOT IN (SELECT parcel_id FROM hatid_payment WHERE sender_id = '{$userId}' AND verify_payment = '1') 
        AND sender_id = '{$userId}'
        AND id IN (SELECT parcel_id FROM notification_list WHERE sender_id = '{$userId}'
        AND notif_type = 0)");
    
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            print(json_encode($result));
        }else{
            print(json_encode(array("1")));
        }
    }
}


if(isset($req['to_pick'])){
    if($req['sKey'] == $skey){
        $userId = $req['userId'];
        $sql = $db->Connection()->query("SELECT * FROM hatid_parcel_info 
        WHERE id IN (SELECT parcel_id FROM notification_list WHERE sender_id = '{$userId}'
        AND notif_type = 1) AND arrive_cat = 1");
    
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            print(json_encode($result));
        }else{
            print(json_encode(array("1")));
        }
    }
}


if(isset($req['to_finish'])){
    if($req['sKey'] == $skey){
        $userId = $req['userId'];
        $sql = $db->Connection()->query("SELECT * FROM hatid_parcel_info 
        WHERE id IN (SELECT parcel_id FROM notification_list WHERE sender_id = '{$userId}'
        AND notif_type = 1) AND arrive_cat = 3");
    
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            print(json_encode($result));
        }else{
            print(json_encode(array("1")));
        }
    }
}


if(isset($req['to_drop'])){
    if($req['sKey'] == $skey){
        $userId = $req['userId'];
        $sql = $db->Connection()->query("SELECT * FROM hatid_parcel_info 
        WHERE id IN (SELECT parcel_id FROM notification_list WHERE sender_id = '{$userId}'
        AND notif_type = 1) AND arrive_cat = 2");
    
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            print(json_encode($result));
        }else{
            print(json_encode(array("1")));
        }
    }
}


if(isset($req['to_change'])){
    if($req['sKey'] == $skey){
        $id = $req['id'];
        $sql = $db->Connection()->prepare("UPDATE hatid_parcel_info SET payment_method = '1'
        WHERE id = '{$id}'");
        
        if($sql->execute()){
            $query = $db->Connection()->prepare("DELETE FROM hatid_payment WHERE parcel_id = '{$id}'");
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

?>