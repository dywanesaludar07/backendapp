<?php 

include 'dbcommon/database.php';
$db = new Database();
$skey = '2021_HatidAppApplication';

parse_str(file_get_contents("php://input"),$req);


if(isset($req['send_feedback'])){
    if($req['sKey'] == $skey){
        $rider_id = $req['rider_id'];
        $orderId = $req['orderId'];
        $feedback = $req['feedback'];

        $sql = $db->Connection()->prepare("INSERT INTO feedback (rider_id,parcel_id,feedback)
        VALUES('{$rider_id}','{$orderId}','{$feedback}')");

        if($sql->execute()){
            print(json_encode(array("0")));
        }else{
            print(json_encode(array("1")));
        }
    }
}
?>