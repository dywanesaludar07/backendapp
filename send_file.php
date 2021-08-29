<?php 

include 'dbcommon/database.php';
$db = new Database();

$name = $_POST['folder_name'];
$files = $_FILES['filePdf']['name'];
$tmp_file = $_FILES['filePdf']['tmp_name'];

$path = "RidersListAccount/";
$destination = $path.$name."/";

if(isset($_FILES['filePdf']['name'])){
        if(is_dir($destination)){
            if(move_uploaded_file($tmp_file,$destination.$files)){
                print(json_encode(array("0")));
            }else{
                print(json_encode(array("1")));
            }
        }else{
            mkdir($path.$name, 0777);
            $path = "RidersListAccount/";
            if(move_uploaded_file($tmp_file,$destination.$files)){
                print(json_encode(array("0")));
            }else{
                print(json_encode(array("1")));
            }
        }
}
?>