<?php
date_default_timezone_set('Asia/Bangkok');

//general settings
define('POI_CHECK_IN_DISTANCE',1.0); //unit: km

//file settings
define('FILE_CHECK_IN_FULLPATH','db/user_checkin.txt');
define('USER_IMAGE_FOLDER','userimage');
define('USER_IMAGE_THUMBNAIL_SUBFOLDER','thumbnails');

//salt settings
define('HASH_ALGORITHM','sha256');
define('SALT','bangchak@corporation' . date('Ymd'));
?>