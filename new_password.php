<?php 

include 'dbcommon/database.php';
$db = new Database();
$skey = '2021_HatidAppApplication';
error_reporting(0);

parse_str(file_get_contents("php://input"),$req);

if(isset($req['password_user'])){
    if($req['sKey'] == $skey){
        $email = $req['email'];
        $password = password_hash($req['password'], PASSWORD_BCRYPT);

        $sql = $db->Connection()->prepare("UPDATE account_table SET password = '{$password}'
        WHERE email_address = '{$email}'");
        if($sql->execute()){
            print(json_encode(array("0")));
        }else{
            print(json_encode(array("1")));
        }
    }
}


if(isset($req['password_rider'])){
    if($req['sKey'] == $skey){
        $email = $req['email'];
        $password = password_hash($req['password'], PASSWORD_BCRYPT);

        $sql = $db->Connection()->prepare("UPDATE account_rider SET password = '{$password}'
        WHERE email_address = '{$email}'");
        if($sql->execute()){
            print(json_encode(array("0")));
        }else{
            print(json_encode(array("1")));
        }
    }
}
?>