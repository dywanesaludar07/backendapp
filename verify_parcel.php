<?php 
    include 'dbcommon/database.php';
    $server = $_SERVER['REQUEST_METHOD'];
    parse_str(file_get_contents("php://input"),$req);
    $db = new Database();
    
    $id = $req['id'];
    $skey = '2021_HatidAppApplication';

    if(isset($req['verify_parcel'])){
        if($req['sKey'] == $skey){
            $sql = $db->Connection()->prepare("UPDATE hatid_parcel_info
            SET verified_by_email = '1' WHERE id = '{$id}'");
            
            if($sql->execute()){
                print(json_encode(array("0")));
            }else{
                print(json_encode(array("1")));
            }
        }
    }
?>