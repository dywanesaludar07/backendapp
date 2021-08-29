<?php include 'dbcommon/database.php';
    $server = $_SERVER['REQUEST_METHOD'];
    parse_str(file_get_contents("php://input"),$req);
    $db = new Database();
    
        $name = $req['name'];
        $contact =  $req['contact'];
        $email =  $req['email'];
        $password = password_hash($req['password'], PASSWORD_BCRYPT);
        $account_type =  $req['groupValue'];
        $address = $req['address'];
        $fields = "(account_type,account_name,contact_number,email_address,address,password,user_type,delete_flg)";
        $sql = $db->Connection()->prepare("INSERT INTO account_table {$fields} 
        VALUES('{$account_type}','{$name}','{$contact}','{$email}','{$address}','{$password}','0','0')");
        if ($sql->execute()){
            print(json_encode(array("0")));
        }else{
            print(json_encode(array("1")));
        }
?>