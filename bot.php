<?php
// กรณีต้องการตรวจสอบการแจ้ง error ให้เปิด 3 บรรทัดล่างนี้ให้ทำงาน กรณีไม่ ให้ comment ปิดไป
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
// include composer autoload
require_once './vendor/autoload.php';
 
// การตั้งเกี่ยวกับ bot
require_once './config/bot_settings.php';
require_once './config/config_value.php';
 
// กรณีมีการเชื่อมต่อกับฐานข้อมูล
//require_once("dbconnect.php");
 
///////////// ส่วนของการเรียกใช้งาน class ผ่าน namespace
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\Event;
use LINE\LINEBot\Event\BaseEvent;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\Event\AccountLinkEvent;
use LINE\LINEBot\Event\MemberJoinEvent; 
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder ;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
 
 
$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
  
// คำสั่งรอรับการส่งค่ามาของ LINE Messaging API
$content = file_get_contents('php://input');
  
// กำหนดค่า signature สำหรับตรวจสอบข้อมูลที่ส่งมาว่าเป็นข้อมูลจาก LINE
$hash = hash_hmac('sha256', $content, LINE_MESSAGE_CHANNEL_SECRET, true);
$signature = base64_encode($hash);
  
// แปลงค่าข้อมูลที่ได้รับจาก LINE เป็น array ของ Event Object
$events = $bot->parseEventRequest($content, $signature);
$eventObj = $events[0]; // Event Object ของ array แรก
  
// ดึงค่าประเภทของ Event มาไว้ในตัวแปร มีทั้งหมด 7 event
$eventType = $eventObj->getType();
  
// สร้างตัวแปร ไว้เก็บ sourceId ของแต่ละประเภท
$userId = NULL;
$groupId = NULL;
$roomId = NULL;
// สร้างตัวแปรเก็บ source id และ source type
$sourceId = NULL;
$sourceType = NULL;
// สร้างตัวแปร replyToken และ replyData สำหรับกรณีใช้ตอบกลับข้อความ
$replyToken = NULL;
$replyData = NULL;
// สร้างตัวแปร ไว้เก็บค่าว่าเป้น Event ประเภทไหน
$eventMessage = NULL;
$eventPostback = NULL;
$eventJoin = NULL;
$eventLeave = NULL;
$eventFollow = NULL;
$eventUnfollow = NULL;
$eventBeacon = NULL;
$eventAccountLink = NULL;
$eventMemberJoined = NULL;
$eventMemberLeft = NULL;
// เงื่อนไขการกำหนดประเภท Event 
switch($eventType){
    case 'message': $eventMessage = true; break;    
    case 'postback': $eventPostback = true; break;  
    case 'join': $eventJoin = true; break;  
    case 'leave': $eventLeave = true; break;    
    case 'follow': $eventFollow = true; break;  
    case 'unfollow': $eventUnfollow = true; break;  
    case 'beacon': $eventBeacon = true; break;     
    case 'accountLink': $eventAccountLink = true; break;       
    case 'memberJoined': $eventMemberJoined = true; break;       
    case 'memberLeft': $eventMemberLeft = true; break;                                           
}
// สร้างตัวแปรเก็บค่า userId กรณีเป็น Event ที่เกิดขึ้นใน USER
if($eventObj->isUserEvent()){
    $userId = $eventObj->getUserId();  
    $sourceType = "USER";
}
// สร้างตัวแปรเก็บค่า groupId กรณีเป็น Event ที่เกิดขึ้นใน GROUP
if($eventObj->isGroupEvent()){
    $groupId = $eventObj->getGroupId();  
    $userId = $eventObj->getUserId();  
    $sourceType = "GROUP";
}
// สร้างตัวแปรเก็บค่า roomId กรณีเป็น Event ที่เกิดขึ้นใน ROOM
if($eventObj->isRoomEvent()){
    $roomId = $eventObj->getRoomId();        
    $userId = $eventObj->getUserId();      
    $sourceType = "ROOM";
}
// เก็บค่า sourceId ปกติจะเป็นค่าเดียวกันกับ userId หรือ roomId หรือ groupId ขึ้นกับว่าเป็น event แบบใด
$sourceId = $eventObj->getEventSourceId();
// ดึงค่า replyToken มาไว้ใช้งาน ทุกๆ Event ที่ไม่ใช่ Leave และ Unfollow Event และ  MemberLeft
// replyToken ไว้สำหรับส่งข้อความจอบกลับ 
if(is_null($eventLeave) && is_null($eventUnfollow) && is_null($eventMemberLeft)){
    $replyToken = $eventObj->getReplyToken();    
}
 
// ส่วนของการทำงาน
if(!is_null($events)){
 
    // ถ้า bot ถูก invite เพื่อเข้า Join Event ให้ bot ส่งข้อความใน GROUP ว่าเข้าร่วม GROUP แล้ว
    if(!is_null($eventJoin)){
        $textReplyMessage = "ขอเข้าร่วมด้วยน่ะ $sourceType ID:: ".$sourceId;
        $replyData = new TextMessageBuilder($textReplyMessage);                 
    }
     
    // ถ้า bot ออกจาก สนทนา จะไม่สามารถส่งข้อความกลับได้ เนื่องจากไม่มี replyToken
    if(!is_null($eventLeave)){
 
    }   
     
    // ถ้า bot ถูกเพื่มเป้นเพื่อน หรือถูกติดตาม หรือ ยกเลิกการ บล็อก
    if(!is_null($eventFollow)){
        //$textReplyMessage = "กรุณาลงทะเบียนก่อนเริ่มใช้งาน คลิกที่นี่ https://liff.line.me/1654959076-a1OMkNOx";        
        //$replyData = new TextMessageBuilder($textReplyMessage);         

        $replyData = new TemplateMessageBuilder('Image Carousel',
                                    new ImageCarouselTemplateBuilder(
                                        array(
                                            new ImageCarouselColumnTemplateBuilder(
                                                'https://bcplineoa.bangchak.co.th/powerbi-club/uploadimage/ic_reg.png',
                                                new UriTemplateActionBuilder(
                                                    'คลิกที่นี่', // ข้อความแสดงในปุ่ม
                                                    'https://liff.line.me/1654945197-jV946Eea'
                                                )
                                            ),
                                        )
                                    )
                                );
        
    }
     
    // ถ้า bot ถูกบล็อก หรือเลิกติดตาม จะไม่สามารถส่งข้อความกลับได้ เนื่องจากไม่มี replyToken
    if(!is_null($eventUnfollow)){
 
    }       
     
    // ถ้ามีสมาชิกคนอื่น เข้ามาร่วมใน room หรือ group 
    // room คือ สมมติเราคุยกับ คนหนึ่งอยู่ แล้วเชิญคนอื่นๆ เข้ามาสนทนาด้วย จะกลายเป็นห้องใหม่
    // group คือ กลุ่มที่เราสร้างไว้ มีชื่อกลุ่ม แล้วเราเชิญคนอื่นเข้ามาในกลุ่ม เพิ่มร่วมสนทนาด้วย
    if(!is_null($eventMemberJoined)){
            $arr_joinedMember = $eventObj->getEventBody();
            $joinedMember = $arr_joinedMember['joined']['members'][0];
            if(!is_null($groupId) || !is_null($roomId)){
                if($eventObj->isGroupEvent()){
                    foreach($joinedMember as $k_user=>$v_user){
                        if($k_user=="userId"){
                            $joined_userId = $v_user;
                        }
                    }                       
                    $response = $bot->getGroupMemberProfile($groupId, $joined_userId);
                }
                if($eventObj->isRoomEvent()){
                    foreach($joinedMember as $k_user=>$v_user){
                        if($k_user=="userId"){
                            $joined_userId = $v_user;
                        }
                    }                   
                    $response = $bot->getRoomMemberProfile($roomId, $joined_userId);    
                }
            }else{
                $response = $bot->getProfile($userId);
            }
            if ($response->isSucceeded()) {
                $userData = $response->getJSONDecodedBody(); // return array     
                // $userData['userId']
                // $userData['displayName']
                // $userData['pictureUrl']
                // $userData['statusMessage']
                $textReplyMessage = 'สวัสดีครับ คุณ '.$userData['displayName'];     
            }else{
                $textReplyMessage = 'สวัสดีครับ ยินดีต้อนรับ';
            }
//        $textReplyMessage = "ยินดีต้อนรับกลับมาอีกครั้ง ".json_encode($joinedMember);
        $replyData = new TextMessageBuilder($textReplyMessage);                     
    }
     
    // ถ้ามีสมาชิกคนอื่น ออกจากก room หรือ group จะไม่สามารถส่งข้อความกลับได้ เนื่องจากไม่มี replyToken
    if(!is_null($eventMemberLeft)){
     
    }   
 
    // ถ้ามีกาาเชื่อมกับบัญชี LINE กับระบบสมาชิกของเว็บไซต์เรา
    if(!is_null($eventAccountLink)){
        // หลักๆ ส่วนนี้ใช้สำรหบัเพิ่มความภัยในการเชื่อมบัญตี LINE กับระบบสมาชิกของเว็บไซต์เรา 
        $textReplyMessage = "AccountLink ทำงาน ".$replyToken." Nonce: ".$eventObj->getNonce();
        $replyData = new TextMessageBuilder($textReplyMessage);                         
    }
             
    // ถ้าเป็น Postback Event
    if(!is_null($eventPostback)){
        $dataPostback = NULL;
        $paramPostback = NULL;
        // แปลงข้อมูลจาก Postback Data เป็น array
        parse_str($eventObj->getPostbackData(),$dataPostback);
        // ดึงค่า params กรณีมีค่า params
        $paramPostback = $eventObj->getPostbackParams();
        // ทดสอบแสดงข้อความที่เกิดจาก Postaback Event
        $textReplyMessage = "ข้อความจาก Postback Event Data = ";        
        $textReplyMessage.= json_encode($dataPostback);
        $textReplyMessage.= json_encode($paramPostback);
        $replyData = new TextMessageBuilder($textReplyMessage);     
    }
    // ถ้าเป้น Message Event 
    if(!is_null($eventMessage)){
         
        // สร้างตัวแปรเก็ยค่าประเภทของ Message จากทั้งหมด 7 ประเภท
        $typeMessage = $eventObj->getMessageType();  
        //  text | image | sticker | location | audio | video | file  
        // เก็บค่า id ของข้อความ
        $idMessage = $eventObj->getMessageId();          
        // ถ้าเป็นข้อความ
        if($typeMessage=='text'){
            $userMessage = $eventObj->getText(); // เก็บค่าข้อความที่ผู้ใช้พิมพ์
        }
        // ถ้าเป็น image
        if($typeMessage=='image'){
 
        }               
        // ถ้าเป็น audio
        if($typeMessage=='audio'){
 
        }       
        // ถ้าเป็น video
        if($typeMessage=='video'){
 
        }   
        // ถ้าเป็น file
        if($typeMessage=='file'){
            $FileName = $eventObj->getFileName();
            $FileSize = $eventObj->getFileSize();
        }               
        // ถ้าเป็น image หรือ audio หรือ video หรือ file และต้องการบันทึกไฟล์
        if(preg_match('/image|audio|video|file/',$typeMessage)){            
            $responseMedia = $bot->getMessageContent($idMessage);
            if ($responseMedia->isSucceeded()) {
                // คำสั่ง getRawBody() ในกรณีนี้ จะได้ข้อมูลส่งกลับมาเป็น binary 
                // เราสามารถเอาข้อมูลไปบันทึกเป็นไฟล์ได้
                $dataBinary = $responseMedia->getRawBody(); // return binary
                // ดึงข้อมูลประเภทของไฟล์ จาก header
                $fileType = $responseMedia->getHeader('Content-Type');    
                switch ($fileType){
                    case (preg_match('/^application/',$fileType) ? true : false):
//                      $fileNameSave = $FileName; // ถ้าต้องการบันทึกเป็นชื่อไฟล์เดิม
                        $arr_ext = explode(".",$FileName);
                        $ext = array_pop($arr_ext);
                        $fileNameSave = time().".".$ext;                            
                        break;                  
                    case (preg_match('/^image/',$fileType) ? true : false):
                        list($typeFile,$ext) = explode("/",$fileType);
                        $ext = ($ext=='jpeg' || $ext=='jpg')?"jpg":$ext;
                        $fileNameSave = time().".".$ext;
                        break;
                    case (preg_match('/^audio/',$fileType) ? true : false):
                        list($typeFile,$ext) = explode("/",$fileType);
                        $fileNameSave = time().".".$ext;                        
                        break;
                    case (preg_match('/^video/',$fileType) ? true : false):
                        list($typeFile,$ext) = explode("/",$fileType);
                        $fileNameSave = time().".".$ext;                                
                        break;                                                      
                }
                $botDataFolder = 'botdata/'; // โฟลเดอร์หลักที่จะบันทึกไฟล์
                $botDataUserFolder = $botDataFolder.$userId; // มีโฟลเดอร์ด้านในเป็น userId อีกขั้น
                if(!file_exists($botDataUserFolder)) { // ตรวจสอบถ้ายังไม่มีให้สร้างโฟลเดอร์ userId
                    mkdir($botDataUserFolder, 0777, true);
                }   
                // กำหนด path ของไฟล์ที่จะบันทึก
                $fileFullSavePath = $botDataUserFolder.'/'.$fileNameSave;
//              file_put_contents($fileFullSavePath,$dataBinary); // เอา comment ออก ถ้าต้องการทำการบันทึกไฟล์
                $textReplyMessage = "บันทึกไฟล์เรียบร้อยแล้ว $fileNameSave";
                $replyData = new TextMessageBuilder($textReplyMessage);
//              $failMessage = json_encode($fileType);              
//              $failMessage = json_encode($responseMedia->getHeaders());
                $replyData = new TextMessageBuilder($failMessage);                      
            }else{
                $failMessage = json_encode($idMessage.' '.$responseMedia->getHTTPStatus() . ' ' . $responseMedia->getRawBody());
                $replyData = new TextMessageBuilder($failMessage);          
            }
        }
        // ถ้าเป็น sticker
        if($typeMessage=='sticker'){
            $packageId = $eventObj->getPackageId();
            $stickerId = $eventObj->getStickerId();
        }
        // ถ้าเป็น location
        if($typeMessage=='location'){
            $locationTitle = $eventObj->getTitle();
            $locationAddress = $eventObj->getAddress();
            $locationLatitude = $eventObj->getLatitude();
            $locationLongitude = $eventObj->getLongitude();
        }       
         
         
        switch ($typeMessage){ // กำหนดเงื่อนไขการทำงานจาก ประเภทของ message
            case 'text':  // ถ้าเป็นข้อความ
                $userMessage = strtolower($userMessage); // แปลงเป็นตัวเล็ก สำหรับทดสอบ
                switch ($userMessage) {
                    case "text":
                        // ถ้าขณะนั้นเป็นการสนทนาใน ROOM หรือ GROUP
                        if(!is_null($groupId) || !is_null($roomId)){
                            if($eventObj->isGroupEvent()){// ถ้าอยู่ใน GROUP
                                $response = $bot->getGroupMemberProfile($groupId, $userId); // ดึงข้อมูลผู้ใช้ที่คุยกับ bot
                            }
                            if($eventObj->isRoomEvent()){ // ถ้าอยู่ใน ROOM
                                $response = $bot->getRoomMemberProfile($roomId, $userId);// ดึงข้อมูลผู้ใช้ที่คุยกับ bot    
                            }
                        }else{ // ถ้าเป็นการสนทนา ระหว่าง BOT
                            $response = $bot->getProfile($userId);
                        }
                        if ($response->isSucceeded()) {
                            $userData = $response->getJSONDecodedBody(); // return array     
                            // $userData['userId']
                            // $userData['displayName']
                            // $userData['pictureUrl']
                            // $userData['statusMessage']
                            $textReplyMessage = 'สวัสดีครับ คุณ '.$userData['displayName'];     
                        }else{
                            $textReplyMessage = 'สวัสดีครับ คุณคือใคร';
                        }
                        $replyData = new TextMessageBuilder($textReplyMessage);                                                 
                        break; 
                    case "image":
                        $picFullSize = 'https://t.ly/X1wt';
                        $picThumbnail = 'https://t.ly/X1wt';
                        $replyData = new ImageMessageBuilder($picFullSize,$picThumbnail);
                        break;
                    case "video":
                        $picThumbnail = 'https://www.mywebsite.com/imgsrc/photos/f/sampleimage/240';
                        $videoUrl = "https://www.mywebsite.com/simplevideo.mp4";                
                        $replyData = new VideoMessageBuilder($videoUrl,$picThumbnail);
                        break;
                    case "audio":
                        $audioUrl = "https://www.mywebsite.com/simpleaudio.mp3";
                        $replyData = new AudioMessageBuilder($audioUrl,27000);
                        break;
                    case "location":
                        $placeName = "ที่ตั้งบริษัท";
                        $placeAddress = "อาคาร M-Tower ถนนสุขุมวิท แขวงพระโขนงใต้ เขตพระโขนง กรุงเทพมหานคร ประเทศไทย";
                        $latitude = 13.695432;
                        $longitude = 100.606070;
                        $replyData = new LocationMessageBuilder($placeName, $placeAddress, $latitude ,$longitude);              
                        break;
                    case "sticker":
                        $stickerID = 22;
                        $packageID = 2;
                        $replyData = new StickerMessageBuilder($packageID,$stickerID);
                        break;      
                    case "cf":
                            $replyData = new TemplateMessageBuilder('Confirm Template',
                                new ConfirmTemplateBuilder(
                                        'Confirm template builder', // ข้อความแนะนหรือบอกวิธีการ หรือคำอธิบาย
                                        array(
                                            new MessageTemplateActionBuilder(
                                                'Yes', // ข้อความสำหรับปุ่มแรก
                                                'YES'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                            ),
                                            new MessageTemplateActionBuilder(
                                                'No', // ข้อความสำหรับปุ่มแรก
                                                'NO' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                            )
                                        )
                                )
                            );
                            break;          
                    case "imagemap":
                        $imageMapUrl = 'https://www.mywebsite.com/imgsrc/photos/w/sampleimagemap';
                        $replyData = new ImagemapMessageBuilder(
                            $imageMapUrl,
                            'This is Title',
                            new BaseSizeBuilder(699,1040),
                            array(
                                new ImagemapMessageActionBuilder(
                                    'test image map',
                                    new AreaBuilder(0,0,520,699)
                                    ),
                                new ImagemapUriActionBuilder(
                                    'http://www.ninenik.com',
                                    new AreaBuilder(520,0,520,699)
                                    )
                            )); 
                        break; 
                    case "t_b":
                        // กำหนด action 4 ปุ่ม 4 ประเภท
                        $actionBuilder = array(
                            new MessageTemplateActionBuilder(
                                'Message Template',// ข้อความแสดงในปุ่ม
                                'This is Text' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'https://www.ninenik.com'
                            ),
                            new DatetimePickerTemplateActionBuilder(
                                'Datetime Picker', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
                                    'action'=>'reservation',
                                    'person'=>5
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'datetime', // date | time | datetime รูปแบบข้อมูลที่จะส่ง ในที่นี้ใช้ datatime
                                substr_replace(date("Y-m-d H:i"),'T',10,1), // วันที่ เวลา ค่าเริ่มต้นที่ถูกเลือก
                                substr_replace(date("Y-m-d H:i",strtotime("+5 day")),'T',10,1), //วันที่ เวลา มากสุดที่เลือกได้
                                substr_replace(date("Y-m-d H:i"),'T',10,1) //วันที่ เวลา น้อยสุดที่เลือกได้
                            ),      
                            new PostbackTemplateActionBuilder(
                                'Postback', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
                                    'action'=>'buy',
                                    'item'=>100
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
    //                          'Postback Text'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),      
                        );
                        $imageUrl = 'https://www.mywebsite.com/imgsrc/photos/w/simpleflower';
                        $replyData = new TemplateMessageBuilder('Button Template',
                            new ButtonTemplateBuilder(
                                    'button template builder', // กำหนดหัวเรื่อง
                                    'Please select', // กำหนดรายละเอียด
                                    $imageUrl, // กำหนด url รุปภาพ
                                    $actionBuilder  // กำหนด action object
                            )
                        );              
                        break;                                          
                    case "p":
                        // ถ้าขณะนั้นเป็นการสนทนาใน ROOM หรือ GROUP
                        if(!is_null($groupId) || !is_null($roomId)){
                            if($eventObj->isGroupEvent()){// ถ้าอยู่ใน GROUP
                                $response = $bot->getGroupMemberProfile($groupId, $userId); // ดึงข้อมูลผู้ใช้ที่คุยกับ bot
                            }
                            if($eventObj->isRoomEvent()){ // ถ้าอยู่ใน ROOM
                                $response = $bot->getRoomMemberProfile($roomId, $userId);// ดึงข้อมูลผู้ใช้ที่คุยกับ bot    
                            }
                        }else{ // ถ้าเป็นการสนทนา ระหว่าง BOT
                            $response = $bot->getProfile($userId);
                        }
                        if ($response->isSucceeded()) {
                            $userData = $response->getJSONDecodedBody(); // return array     
                            // $userData['userId']
                            // $userData['displayName']
                            // $userData['pictureUrl']
                            // $userData['statusMessage']
                            $textReplyMessage = 'สวัสดีครับ คุณ '.$userData['displayName'];     
                        }else{
                            $textReplyMessage = 'สวัสดีครับ คุณคือใคร';
                        }
                        $replyData = new TextMessageBuilder($textReplyMessage);                                                 
                        break;                          
                    case "l": // เงื่อนไขทดสอบถ้ามีใครพิมพ์ L ใน GROUP / ROOM แล้วให้ bot ออกจาก GROUP / ROOM
                            $sourceId = $eventObj->getEventSourceId();
                            if($eventObj->isGroupEvent()){
                                $bot->leaveGroup($sourceId);
                            }
                            if($eventObj->isRoomEvent()){
                                $bot->leaveRoom($sourceId);  
                            }                                                                                         
                        break;
                    case "a":  // เงื่อนไขกรณีต้องการ เชื่อม Line  account กับ ระบบสมาชิกของเว็บไซต์เรา
                        $response = $httpClient->post("https://api.line.me/v2/bot/user/".urlencode($userId)."/linkToken",array());
                        $result = json_decode($response->getRawBody(),TRUE);
                        // กำหนด action 4 ปุ่ม 4 ประเภท
                        $actionBuilder = array(
                            new UriTemplateActionBuilder(
                                'Account Link', // ข้อความแสดงในปุ่ม
                                'https://www.example.com/link.php?linkToken='.$result['linkToken']
                            ) 
                        );
                        $imageUrl = ''; //กำหนด url รุปภาพ ถ้ามี
                        $replyData = new TemplateMessageBuilder('Button Template',
                            new ButtonTemplateBuilder(
                                    'Account Link', // กำหนดหัวเรื่อง
                                    'Please select', // กำหนดรายละเอียด
                                    $imageUrl, // กำหนด url รุปภาพ
                                    $actionBuilder  // กำหนด action object
                            )
                        );       
                        break;
                        case "whatis":
                            // กำหนด action 4 ปุ่ม 4 ประเภท
                            $actionBuilder0 = array(
                                new MessageTemplateActionBuilder(
                                    'Message Template',// ข้อความแสดงในปุ่ม
                                    'This is Text' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                ),
                                new UriTemplateActionBuilder(
                                    'Uri Template', // ข้อความแสดงในปุ่ม
                                    'https://www.ninenik.com'
                                ),
                                new PostbackTemplateActionBuilder(
                                    'Postback', // ข้อความแสดงในปุ่ม
                                    http_build_query(array(
                                        'action'=>'buy',
                                        'item'=>100
                                    )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                    'Postback Text'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                ),      
                            );
                            $actionBuilder1 = array(
                                new UriTemplateActionBuilder(
                                    'ทำความรู้จัก Power BI', // ข้อความแสดงในปุ่ม
                                    'https://youtu.be/yKTSLffVGbk'
                                ),
                                new UriTemplateActionBuilder(
                                    'ประเภทของ Dashboard',// ข้อความแสดงในปุ่ม
                                    'https://liff.line.me/1654945197-dKgbrZlR'
                                ),
                                new UriTemplateActionBuilder(
                                    'Cooking Steps',// ข้อความแสดงในปุ่ม
                                    'https://liff.line.me/1654945197-be2v6lWG'
                                ),      
                            );
                            $actionBuilder2 = array(
                                new UriTemplateActionBuilder(
                                    'ตัวอย่างการใช้งานเบื้องต้น',// ข้อความแสดงในปุ่ม
                                    'https://www.youtube.com/watch?v=WSvkcRjTBMQ'
                                ),
                                new UriTemplateActionBuilder(
                                    'อ่านต่อที่ ThepExcel', // ข้อความแสดงในปุ่ม
                                    'https://www.thepexcel.com/what-is-power-bi/'
                                ),
                                new UriTemplateActionBuilder(
                                    'อ่านต่อที่ DataProteins', // ข้อความแสดงในปุ่ม
                                    'https://www.facebook.com/DataProteins/'
                                ),     
                            );
                            $actionBuilder3 = array(
                                new UriTemplateActionBuilder(
                                    'EIS Smartport',// ข้อความแสดงในปุ่ม
                                    'https://liff.line.me/1654945197-dV18AlkY'
                                ),
                                new UriTemplateActionBuilder(
                                    'Refinery Dashboard', // ข้อความแสดงในปุ่ม
                                    'https://liff.line.me/1654945197-6Je5g3J7'
                                ),
                                new UriTemplateActionBuilder(
                                    'Example 1', // ข้อความแสดงในปุ่ม
                                    'https://liff.line.me/1654945197-eEZ5vXkj'
                                ),     
                            );
                            $actionBuilder4 = array(
                                new UriTemplateActionBuilder(
                                    'eBook (MS)',// ข้อความแสดงในปุ่ม
                                    'https://bcplineoa.bangchak.co.th/powerbi-club/pdf/eBook_MS.pdf'
                                ),
                                new UriTemplateActionBuilder(
                                    '- Coming Soon -', // ข้อความแสดงในปุ่ม
                                    'https://bcplineoa.bangchak.co.th/powerbi-club/pdf/eBook_MS.pdf'
                                ),
                                new UriTemplateActionBuilder(
                                    '- Coming Soon -', // ข้อความแสดงในปุ่ม
                                    'https://bcplineoa.bangchak.co.th/powerbi-club/pdf/eBook_MS.pdf'
                                ),     
                            );
                            $actionBuilder5 = array(
                                new UriTemplateActionBuilder(
                                    '- Coming Soon -',// ข้อความแสดงในปุ่ม
                                    'https://liff.line.me/1654945197-be2v6lWG'
                                ),
                                new UriTemplateActionBuilder(
                                    '- Coming Soon -', // ข้อความแสดงในปุ่ม
                                    'https://liff.line.me/1654945197-be2v6lWG'
                                ),
                                new UriTemplateActionBuilder(
                                    '- Coming Soon -', // ข้อความแสดงในปุ่ม
                                    'https://liff.line.me/1654945197-be2v6lWG'
                                ),     
                            );
                            $actionBuilder6 = array(
                                new UriTemplateActionBuilder(
                                    '- New1 -',// ข้อความแสดงในปุ่ม
                                    'https://bcplineoa.bangchak.co.th/powerbi-club/showimg.php?imgid=6'
                                ),
                                new UriTemplateActionBuilder(
                                    '- New2 -', // ข้อความแสดงในปุ่ม
                                    'https://bcplineoa.bangchak.co.th/powerbi-club/showimg.php?imgid=6'
                                ),
                                new UriTemplateActionBuilder(
                                    '- New3 -', // ข้อความแสดงในปุ่ม
                                    'https://bcplineoa.bangchak.co.th/powerbi-club/showimg.php?imgid=6'
                                ),     
                            );
                            $replyData = new TemplateMessageBuilder('Carousel',
                                new CarouselTemplateBuilder(
                                    array(
                                        new CarouselColumnTemplateBuilder(
                                            'EP.01',
                                            'เริ่มต้นกับ Power BI',
                                            'https://bcplineoa.bangchak.co.th/powerbi-club/uploadimage/c1.png',
                                            $actionBuilder1
                                        ),
                                        new CarouselColumnTemplateBuilder(
                                            'EP.02',
                                            'Power BI ทำอะไรได้บ้าง',
                                            'https://bcplineoa.bangchak.co.th/powerbi-club/uploadimage/c2.png',
                                            $actionBuilder2
                                        ),
                                        new CarouselColumnTemplateBuilder(
                                            'EP.03',
                                            'มาดู Case Study กันดีกว่า',
                                            'https://bcplineoa.bangchak.co.th/powerbi-club/uploadimage/c3.png',
                                            $actionBuilder3
                                        ),   
                                        new CarouselColumnTemplateBuilder(
                                            'EP.04',
                                            'Power BI eBooks',
                                            'https://bcplineoa.bangchak.co.th/powerbi-club/uploadimage/c4.png',
                                            $actionBuilder4
                                        ),
                                        new CarouselColumnTemplateBuilder(
                                            'EP.05',
                                            'ทำความรู้จัก ETL',
                                            'https://bcplineoa.bangchak.co.th/powerbi-club/uploadimage/c5.png',
                                            $actionBuilder5
                                        ),                                         
                                        new CarouselColumnTemplateBuilder(
                                            'EP.06',
                                            'New EP',
                                            'https://bcplineoa.bangchak.co.th/powerbi-club/uploadimage/c6.png',
                                            $actionBuilder5
                                        ),                                      )
                                )
                            );
                            break;
                        case "help":
                                $replyData = new TemplateMessageBuilder('Image Carousel',
                                    new ImageCarouselTemplateBuilder(
                                        array(
                                            new ImageCarouselColumnTemplateBuilder(
                                                'https://bcplineoa.bangchak.co.th/powerbi-club/uploadimage/ic01.png',
                                                new UriTemplateActionBuilder(
                                                    'คลิกที่นี่', // ข้อความแสดงในปุ่ม
                                                    'https://liff.line.me/1654945197-Ml7QkBvE'
                                                )
                                            ),
                                            new ImageCarouselColumnTemplateBuilder(
                                                'https://bcplineoa.bangchak.co.th/powerbi-club/uploadimage/ic02.png',
                                                new UriTemplateActionBuilder(
                                                    'คลิกที่นี่', // ข้อความแสดงในปุ่ม
                                                    'https://liff.line.me/1654945197-JOLKnvX7'
                                                )
                                            )                                       
                                        )
                                    )
                                );
                            break;
                    default:
                        $textReplyMessage = " คุณไม่ได้พิมพ์ ค่า ตามที่กำหนด";
                        $replyData = new TextMessageBuilder($textReplyMessage);         
                        break;                                      
                }
                break;                                                  
            default:
                if(!is_null($replyData)){
                     
                }else{
                    // กรณีทดสอบเงื่อนไขอื่นๆ ผู้ใช้ไม่ได้ส่งเป็นข้อความ
                    $textReplyMessage = 'สวัสดีครับ คุณ '.$typeMessage;         
                    $replyData = new TextMessageBuilder($textReplyMessage);         
                }
                break;  
        }
    }
}
 
$response = $bot->replyMessage($replyToken,$replyData);
if ($response->isSucceeded()) {
    echo 'Succeeded!';
    return;
}
// Failed
echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
?>