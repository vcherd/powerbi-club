<?php
require_once 'config/config.php';

$imgfolder = USER_IMAGE_FOLDER . '/' . $_GET["userID"] . '/';
$thumbfolder = $imgfolder . USER_IMAGE_THUMBNAIL_SUBFOLDER . '/';

function createThumbs( $pathToImages, $pathToThumbs, $thumbWidth ) 
{
  // open the directory
  $dir = opendir( $pathToImages );

  // loop through it, looking for any/all JPG files:
  while (false !== ($fname = readdir( $dir ))) {
    // parse path for the extension
    $info = pathinfo($pathToImages . $fname);
    // continue only if this is a JPEG image
    if ( strtolower($info['extension']) == 'jpg' ) 
    {
      //echo "input: " . $pathToImages . $fname . "<br>";
      //echo "output: " . $pathToThumbs . "<br>";
      echo "Creating thumbnail for {$fname} <br />";
    
      echo "{$pathToThumbs}{$fname}";
    }
  }
  // close the directory
  closedir( $dir );
}



if(!file_exists($imgfolder)) { // ตรวจสอบถ้ายังไม่มีให้สร้างโฟลเดอร์ userId
    mkdir($imgfolder, 0777, true);
}
if(!file_exists($thumbfolder)) { // ตรวจสอบถ้ายังไม่มีให้สร้างโฟลเดอร์ userId
    mkdir($thumbfolder, 0777, true);
}

createThumbs($imgfolder, $thumbfolder,100);

echo "success";
?>