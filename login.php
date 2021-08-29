<?php include 'dbcommon/database.php';
$db = new Database();
$skey = '2021_HatidAppApplication';

parse_str(file_get_contents("php://input"),$req);
if(isset($req['loginUser'])){
    if($req['sKey'] == $skey){
        $email = $req['email'];
        $password = $req['password'];

        $sql = $db->Connection()->query("SELECT id,password
        FROM account_table WHERE email_address = '{$email}' AND delete_flg = 0");
        $sql->execute();
        if($sql->rowCount() > 0){
            $result = $sql->fetchAll(PDO::FETCH_OBJ);
            foreach ($result as $key => $row) {
                if(password_verify($req['password'],$row->password)){
                    print(json_encode(array($row->id)));
                }else{
                    print(json_encode(array("err_no")));
                }
            }
        }else{
			print(json_encode(array("err_no")));
		}
    }
}
else if(isset($req['loginRider'])){
    if($req['sKey'] == $skey){
        $email = $req['email'];
        $password = $req['password'];

        $sql = $db->Connection()->query("SELECT id,password,verify
        FROM account_rider WHERE email_address = '{$email}'");
        $sql->execute();
        if($sql->rowCount() > 0){
            $result = $sql->fetchAll(PDO::FETCH_OBJ);
            foreach ($result as $key => $row) {
                if(password_verify($req['password'],$row->password)){
                    print(json_encode(array($row->id,$row->verify)));
                }else{
                    print(json_encode(array("err_no")));
                }
            }
        }else{
			print(json_encode(array("err_no")));
		}
    }
}
?>