<?php

require_once 'config/config.php';

$imgfolder = USER_IMAGE_FOLDER . '/' . $_GET["userID"] . '/';
$thumbfolder = $imgfolder . USER_IMAGE_THUMBNAIL_SUBFOLDER . '/';

$allowed_types=array('jpg','jpeg','gif','png');
$dir    = $imgfolder;
$files1 = scandir($dir);
$total=0; // นับจำนวนรูปทั้งหมด
$pic_path=array();
foreach($files1 as $key=>$value){
    if($key>1){
        $file_parts = explode('.',$value);
        $ext = strtolower(array_pop($file_parts));
        if(in_array($ext,$allowed_types)){
            $pic_path[]=$dir.$value;
            $total++;
        }
    }
}

//echo "Total = " . $total . "<BR>";

// จำนวนรายการที่ต้องการแสดง แต่ละหน้า
$perPage = 2;
 
// คำนวณจำนวนหน้าทั้งหมด
$num_naviPage=ceil($total/$perPage);
 
// กำหนดจุดเริ่มต้น และสิ้นสุดของรายการแต่ละหน้าที่จะแสดง
if(!isset($_GET['page'])){
    $s_key=0;
    $e_key=$perPage;    
    $_GET['page']=1;
}else{
    $s_key=($_GET['page']*$perPage)-$perPage;
    $e_key=$perPage*$_GET['page'];
    $e_key=($e_key>$total)?$total:$e_key;
}
for($i=1;$i<=$num_naviPage;$i++){
    echo "  || <a href=\"?page=".$i."\">Page $i</a>";   
    //echo '<button id="SubmitBtn" type="submit">Page $i</button>';

}
echo "<hr>";
 
// แสดงรายการ
for($indexPicture=$s_key;$indexPicture<$e_key;$indexPicture++){
 
        echo "<img style='width:100px;' src='".$pic_path[$indexPicture]."'/>&nbsp;";  
}


?>