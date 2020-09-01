<?php
require_once 'config/config.php';

$imgfolder = USER_IMAGE_FOLDER . '/' . $_GET["userID"] . '/';
$thumbfolder = $imgfolder . USER_IMAGE_THUMBNAIL_SUBFOLDER . '/';

function createThumbs( $pathToImages, $pathToThumbs, $thumbWidth ) 
{
// Turn off all error reporting
error_reporting(0);

set_time_limit(0);
  // open the directory
  $dir = opendir( $pathToImages );

  // loop through it, looking for any/all JPG files:
  $i='1';
  while (false !== ($fname = readdir( $dir ))) {
    // parse path for the extension
    $info = pathinfo($pathToImages . $fname);
    // continue only if this is a JPEG image
        $source_file_name = basename($source_image);
        $source_image_type = substr($source_file_name, -3, 3);

        switch(strtolower($info['extension']))
        {
        case 'jpg':
            $img = imagecreatefromjpeg("{$pathToImages}{$fname}");
            break;

        case 'gif':
            $img = imagecreatefromgif("{$pathToImages}{$fname}");
            break;

        case 'png':
            $img = imagecreatefrompng("{$pathToImages}{$fname}");
            break;    
        }

      echo "$i : Creating thumbnail for small_$fname <br />";

      // load image and get image size
      $width = imagesx( $img );
      $height = imagesy( $img );

    // this will be our cropped image

    // copy the crop area from the source image to the blank image created above

    // calculate thumbnail size
      $new_width = $thumbWidth;
      $new_height = $thumbWidth;

      // create a new tempopary image
      $tmp_img = imagecreatetruecolor( $new_width, $new_height );


      // copy and resize old image into new image 
      imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );


    switch(strtolower($info['extension']))
    {
        case 'jpg':
            imagejpeg($tmp_img, "{$pathToThumbs}small_$fname", 100);
            break;

        case 'gif':
            imagegif($tmp_img, "{$pathToThumbs}small_$fname");
            break;

        case 'png':
            imagepng($tmp_img,"{$pathToThumbs}small_$fname", 0);
            break;    
    }
    imagedestroy($img);
    imagedestroy($tmp_img);
    $i++;
      }
  // close the directory
  closedir( $dir );
}

createThumbs($imgfolder, $thumbfolder,100);

echo "success";
?>