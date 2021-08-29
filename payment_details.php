<?php 

include 'dbcommon/database.php';
$db = new Database();
$skey = '2021_HatidAppApplication';

if(isset($_POST['sendPayment'])){
    if($_POST['sKey'] == $skey){

        $fileName = $_POST['fileName'];
        $orderId = $_POST['orderId'];
        $payment_type = $_POST['payment_type'];
        $original_file = $_FILES['fileImage']['name'];
        $tmp_file = $_FILES['fileImage']['tmp_name'];
        $extension = pathinfo($original_file, PATHINFO_EXTENSION);

        $new_file = $fileName.".".$extension;
        $path = "imgs/receipts/";

        if(move_uploaded_file($tmp_file,$path.$new_file)){
            $sql = $db->Connection()->query("SELECT * FROM hatid_parcel_info
            WHERE id = '{$orderId}'");

            $result = $sql->fetchAll(PDO::FETCH_OBJ);
            
            if(!empty($result)){
                foreach ($result as $key => $row) {
                    $fields = "(rider_id,sender_id,payment,delivery_fee,parcel_id,hatid_payment.range,payment_type,verify_payment)";
                    $sql = $db->Connection()->prepare("INSERT INTO hatid_payment {$fields}
                    VALUES('{$row->on_deliver}','{$row->sender_id}','{$row->total_fee}'
                    ,'0','{$row->id}','{$row->distance}','{$payment_type}','0')");

                    if($sql->execute()){
                        print(json_encode(array("0")));
                    }else{
                        print(json_encode(array("1")));
                    }
                }
            }
        }else{
            print(json_encode(array("1")));
        }
    }   
}
?>