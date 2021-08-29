<?php 

include 'dbcommon/database.php';
$db = new Database();
$skey = '2021_HatidAppApplication';
error_reporting(0);
parse_str(file_get_contents("php://input"),$req);



if(isset($req['remove_parcel'])){
    if($req['sKey'] == $skey){
        $parcelId = $req['parcel_id'];
        $rider_id = $req['rider_id'];
        $sql = $db->Connection()->prepare("UPDATE hatid_parcel_info SET on_deliver = 0 
        WHERE id = '{$parcelId}' AND on_deliver = '{$rider_id}'");
        
        if($sql->execute()){
            $query = $db->Connection()->prepare("INSERT INTO cancelled_parcel
            (rider_id,parcel_id,create_date) VALUES ('{$rider_id}','{$parcelId}', NOW())");

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



if(isset($req['finish_delivery'])){
    if($req['sKey'] == $skey){
        $parcelId = $req['parcel_id'];
        $rider_id = $req['rider_id'];
	
		
        $sql = $db->Connection()->query("SELECT * FROM hatid_payment WHERE rider_id = '{$rider_id}'
        AND parcel_id = '{$parcelId}'");

        $result = $sql->fetchAll(PDO::FETCH_OBJ);
        if(!empty($result)){

            $sql = $db->Connection()->prepare("UPDATE hatid_payment SET verify_payment = 1
            WHERE parcel_id = '{$parcelId}'");
            $sql->execute();

            foreach ($result as $key => $row) {
                $fields = "(rider_id,delivery_fee,created_date,parcel_id,distance)";
                $query = $db->Connection()->prepare("INSERT INTO payment_history {$fields}
                VALUES('{$rider_id}','{$row->payment}',NOW(),'{$parcelId}','{$row->range}')");
                if($query->execute()){
                    print(json_encode(array("0")));
                }else{
                    print(json_encode(array("1")));
                }
            }
        }else{
			
			$query = $db->Connection()->query("SELECT * FROM hatid_parcel_info WHERE id = '{$parcelId}'
			AND on_deliver = '{$rider_id}'");
			$result = $query->fetchAll(PDO::FETCH_OBJ);
			
			
            foreach ($result as $key => $row) {
				$query = $db->Connection()->prepare("INSERT INTO hatid_payment
				(rider_id,sender_id,payment,delivery_fee,parcel_id,hatid_payment.range,payment_type,verify_payment)
				VALUES('{$row->on_deliver}','{$row->sender_id}','{$row->total_fee}','{$row->total_fee}','{$parcelId}',
				'{$row->distance}','{$row->payment_method}','0')");
				
				if($query->execute()){
					$sql = $db->Connection()->query("SELECT * FROM hatid_payment WHERE rider_id = '{$rider_id}'
					AND parcel_id = '{$parcelId}'");

					$result = $sql->fetchAll(PDO::FETCH_OBJ);
					if(!empty($result)){

						$sql = $db->Connection()->prepare("UPDATE hatid_payment SET verify_payment = 1
						WHERE parcel_id = '{$parcelId}'");
						$sql->execute();

						foreach ($result as $key => $row) {
							$fields = "(rider_id,delivery_fee,created_date,parcel_id,distance)";
							$query = $db->Connection()->prepare("INSERT INTO payment_history {$fields}
							VALUES('{$rider_id}','{$row->payment}',NOW(),'{$parcelId}','{$row->range}')");
							if($query->execute()){
								print(json_encode(array("0")));
							}else{
								print(json_encode(array("1")));
							}
						}
					}
				}else{
					exit(json_encode(array("1")));
				}
			}
        }
    }
}

?>