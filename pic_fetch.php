<?php
error_reporting(0);
$defImg = 'sad';
$img_path = realpath('imgs/receipts');

if (isset($_GET['url']) && !empty($_GET['url'])) {
	 $temp = $_GET['url'];
     $image_file = $_GET['url'];
}else{
    $image_file = $defImg;
}


    
$mimeType = array('.jpg','.png','.jpeg','.gif');
if(strstr($image_file ,'http')=== false){
    for($i = 0 ;$i< count($mimeType) ; ++$i){
        if(file_exists($img_path."/".$image_file.$mimeType[$i])){
           $image_file = $img_path."/".$image_file.$mimeType[$i];
        }
    }
}

if($image_file == $temp){
      $image_file = $img_path."/".$defImg.".png";
}

//Get the file size of the original image
list($original_width, $original_height) = getimagesize($image_file);
 
// $new_width = $original_width;,
$new_width = 500;
if (!empty($_GET['width'])) {
    $new_width = $_GET['width'];
    }

//Calculate the ratio of the original image and set the height
$proportion = $original_width / $original_height;
$new_height = $new_width / $proportion;
 
//If the height is larger than the width, adjust the height to the width and reduce the width
if($proportion < 1){
    $new_height = $new_width;
    $new_width = $new_width * $proportion;
    }
 
$file_type = strtolower(end(explode('.', $image_file)));

if ($file_type === "jpg" || $file_type === "jpeg" || $file_type === "JPG") {
    $original_image = ImageCreateFromJPEG($image_file); //Read a JPEG file
    $new_image = ImageCreateTrueColor($new_width, $new_height); // Image creation
    } 

else if ($file_type === "gif") {
 
    $original_image = ImageCreateFromGIF($image_file); //Read GIF file
    $new_image = ImageCreateTrueColor($new_width, $new_height); // Image creation
 
    /* ----- Transparency problem solving ------ */
    $alpha = imagecolortransparent($original_image); //Get transparent color from original image
    imagefill($new_image, 0, 0, $alpha); //Fill the canvas with that color
    imagecolortransparent($new_image, $alpha); //Specify the filled color as a transparent color
 
} 

else if ($file_type === "png") {
 
    $original_image = ImageCreateFromPNG($image_file); //Read PNG file
    $new_image = ImageCreateTrueColor($new_width, $new_height); //Image creation
 
    /* ----- Transparency problem solving ------ */
    imagealphablending($new_image, false); //Turn off alpha blending
    imagesavealpha($new_image, true); //Turn on the flag to store complete alpha channel information
 
} else {
    // Please note that the processing is not written when nothing is applied!
    return;
}
 
// Resampling from original image
ImageCopyResampled($new_image,$original_image,0,0,0,0,$new_width,$new_height,$original_width,$original_height);
 
// Display image in browser
if ($file_type === "jpg" || $file_type === "jpeg" || $file_type === "JPG") {
    ImageJPEG($new_image);
} elseif ($file_type === "gif") {
    ImageGIF($new_image);
} elseif ($file_type === "png") {
    ImagePNG($new_image);
}
 
// Free memory
imagedestroy($new_image);
imagedestroy($original_image);


?>

