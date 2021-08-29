<?php include 'dbcommon/database.php';
    error_reporting(0);
    $db = new Database();
	$sql = $db->Connection()->prepare("INSERT INTO hatid_parcel_info (description,product_quality,pickup_location,staddress,receiver_name,receiver_contact,drop_location,staddress2,payment_method,     total_fee,category,sender_id,on_deliver,create_date,verified_by_email,sender_lat,sender_long,receiver_lat,receiver_long,distance)
		VALUES('testtttt','0','Rosarito, Baja California, Mexico','Testtt','hhh','09511664848','Noveleta, Cavite, Philippines',
		'Blkkkhhhs','0','71774.42','0','16','0', NOW(),'0','32.3661011','-117.0617553'
		,'14.4278929','120.8801182','11954.07')");



    if($sql->execute()){
	  print("inserted");
	}else{
	  print("not inserted");
	}
?>  

