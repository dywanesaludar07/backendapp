<?php include 'dbcommon/database.php';
$db = new Database();

$name = $_POST['fileName'];
$files = $_FILES['fileImage']['name'];
$tmp_file = $_FILES['fileImage']['tmp_name'];

$file_extension = pathinfo($files,PATHINFO_EXTENSION);


$path = "imgs/sender_profile/";
$destination = $path.$name.".".$file_extension;

if(isset($_FILES['fileImage']['name'])){
            if(move_uploaded_file($tmp_file,$destination)){
                print(json_encode(array("0")));
            }else{
                print(json_encode(array("1")));
            }
}
?>