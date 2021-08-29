<?php 
if(!headers_sent() && empty(session_id())){
    session_start();
}

include 'dbcommon/database.php';
parse_str(file_get_contents("php://input"),$req);

/***REQUESTED EMAIL */
$db = new Database();
$email = $req['email'];

if(isset($req['createUser'])){
        
        $sql = $db->Connection()->query("SELECT id FROM account_rider
        WHERE email_address = '{$email}'");
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);

        if(!empty($result)){
             print(json_encode(array("3")));
        }else{

        $password = password_hash($req['password'], PASSWORD_BCRYPT);
        $name = $req['name'];
        $contact = $req['contact'];
        $email = $req['email'];
        $address = $req['address'];
        $sql = $db->Connection()->prepare("INSERT INTO account_rider 
        (account_name,contact_number,email_address,address,password,verify)
        VALUES('{$name}','{$contact}','{$email}','{$address}','{$password}','0')");

        if($sql->execute()){
            print(json_encode(array("0")));
        }else{
            print(json_encode(array("1")));
        }
    }
}
?>