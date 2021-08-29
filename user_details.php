<?php 

include 'dbcommon/database.php';
$db = new Database();
$skey = '2021_HatidAppApplication';
parse_str(file_get_contents("php://input"),$req);

if(isset($req['get_details'])){
    if($skey == $req['sKey']){
        $id = $req['userId'];
        $sql = $db->Connection()->query("SELECT * FROM account_table 
        WHERE id = '{$id}'");

        $sql->execute();
        
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
            print(json_encode(array($result)));
        }else{
            print(json_encode(array("0")));
        }
    }
}


if(isset($req['update_details'])){
    if($skey == $req['sKey']){
        $id = $req['userId'];
        $password = password_hash($req['new_password'], PASSWORD_BCRYPT);
        $sql = $db->Connection()->query("UPDATE account_table SET password = '{$password}'
        WHERE id = '{$id}'");

        if($sql->execute()){
            print(json_encode(array("0")));
        }else{
            print(json_encode(array("1")));
        }
    }
}

if(isset($req['update_address'])){
    if($skey == $req['sKey']){
        $id = $req['userId'];
        $address = $req['new_address'];
        $sql = $db->Connection()->query("UPDATE account_table SET address = '{$address}'
        WHERE id = '{$id}'");

        if($sql->execute()){
            print(json_encode(array("0")));
        }else{
            print(json_encode(array("1")));
        }
    }
}
?>