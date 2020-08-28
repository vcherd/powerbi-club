<?php
// กรณีต้องการตรวจสอบการแจ้ง error ให้เปิด 3 บรรทัดล่างนี้ให้ทำงาน กรณีไม่ ให้ comment ปิดไป
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
// include composer autoload
require_once '../../vendor/autoload.php';
// กรณีมีการเชื่อมต่อกับฐานข้อมูล
//require_once("dbconnect.php");
 
// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;  
 
if(isset($_GET['file']) && $_GET['file']!=""){
    $picFile = trim($_GET['file']);
    $originalFilePath = '../../uploadimage/'; // แก้ไขเป็นโฟลเดอร์รูปต้นฉบับ
    $fullFilePath = $originalFilePath.$picFile;
    $fullFilePathJPG = $fullFilePath.'.jpg';
    $fullFilePathPNG = $fullFilePath.'.png';
    $fullFile = '';
    $picType = '';
    if(file_exists($fullFilePathJPG)){
        $picType = 'jpg';
        $fullFile = $fullFilePath.'.'.$picType;
    }
    if(file_exists($fullFilePathPNG)){
        $picType = 'png';
        $fullFile = $fullFilePath.'.'.$picType;
    }   
    if($picType==''){
        header("HTTP/1.0 404 Not Found");
        exit;
    }
    // สร้างตัวแปรอ้างอิง object ตัวจัดการรูปภาพ
    $manager = new ImageManager();    
    $img = $manager->make($fullFile);     
     
    $width = (isset($_GET['width']) && $_GET['width']!="")?$_GET['width']:NULL;
    $height = (isset($_GET['height']) && $_GET['height']!="")?$_GET['height']:NULL;
    $mode = (isset($_GET['mode']) && $_GET['mode']!="")?$_GET['mode']:NULL;
     
    if(!is_null($width) && !is_null($height)){
        switch ($mode) {
            case 'f':
                $img->fit($width, $height, function ($constraint) {
                    $constraint->upsize(); // ถ้าค่าที่กำหนดมากกว่าค่าเดิม ไม่ต้องปรับขนาด
                }); 
                break;  
            case 'c':
                $img->crop($width, $height); 
                break;  
            case 'r':
                $img->resize($width, $height);   
                break;  
            case 'w':
                $img->widen($width, function ($constraint) {
                    $constraint->upsize(); // ถ้าค่าที่กำหนดมากกว่าค่าเดิม ไม่ต้องปรับขนาด
                }); 
                break;          
            case 'h':
                $img->heighten($height, function ($constraint) {
                    $constraint->upsize(); // ถ้าค่าที่กำหนดมากกว่าค่าเดิม ไม่ต้องปรับขนาด
                }); 
                break;                                                                  
        }
    }else{
        if(!is_null($width)){
            switch ($mode) {
                case 'f':
                    $img->fit($width);   
                    break;  
                case 'c':
                    $img->crop($width, $width);  
                    break;  
                case 'r':
                    $img->resize($width, null, function ($constraint) {
                        $constraint->aspectRatio();// ให้คงสัดส่วนของรูปภาพ
                    }); 
                    break;  
                case 'w':
                    $img->widen($width, function ($constraint) {
                        $constraint->upsize(); // ถ้าค่าที่กำหนดมากกว่าค่าเดิม ไม่ต้องปรับขนาด
                    }); 
                    break;          
                case 'h':
                    $img->heighten($width, function ($constraint) {
                        $constraint->upsize(); // ถ้าค่าที่กำหนดมากกว่าค่าเดิม ไม่ต้องปรับขนาด
                    }); 
                    break;      
            }
        }
    }
     
    // ส่ง HTTP header และข้อมูลของรูปเพื่อนำไปแสดง
    echo $img->response();           
}else{
    header("HTTP/1.0 404 Not Found");
    exit;   
}
?>