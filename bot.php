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
//use LINE\LINEBot\Event;
//use LINE\LINEBot\Event\BaseEvent;
//use LINE\LINEBot\Event\MessageEvent;
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

use LINE\LINEBot\Constant\Flex\ComponentIconSize;
use LINE\LINEBot\Constant\Flex\ComponentImageSize;
use LINE\LINEBot\Constant\Flex\ComponentImageAspectRatio;
use LINE\LINEBot\Constant\Flex\ComponentImageAspectMode;
use LINE\LINEBot\Constant\Flex\ComponentFontSize;
use LINE\LINEBot\Constant\Flex\ComponentFontWeight;
use LINE\LINEBot\Constant\Flex\ComponentMargin;
use LINE\LINEBot\Constant\Flex\ComponentSpacing;
use LINE\LINEBot\Constant\Flex\ComponentButtonStyle;
use LINE\LINEBot\Constant\Flex\ComponentButtonHeight;
use LINE\LINEBot\Constant\Flex\ComponentSpaceSize;
use LINE\LINEBot\Constant\Flex\ComponentGravity;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\Flex\BubbleStylesBuilder;
use LINE\LINEBot\MessageBuilder\Flex\BlockStyleBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\CarouselContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ButtonComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\IconComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ImageComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SpacerComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\FillerComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SeparatorComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;

$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
 
// คำสั่งรอรับการส่งค่ามาของ LINE Messaging API
$content = file_get_contents('php://input');
 
// แปลงข้อความรูปแบบ JSON  ให้อยู่ในโครงสร้างตัวแปร array
$events = json_decode($content, true);
if(!is_null($events)){
    // ถ้ามีค่า สร้างตัวแปรเก็บ replyToken ไว้ใช้งาน
    $replyToken = $events['events'][0]['replyToken'];
    $userID = $events['events'][0]['source']['userId'];
    $sID = hash(HASH_ALGORITHM, $userID . SALT);
    $sourceType = $events['events'][0]['source']['type'];        
    $is_postback = NULL;
    $is_message = NULL;
    if(isset($events['events'][0]) && array_key_exists('message',$events['events'][0])){
        $is_message = true;
        $typeMessage = $events['events'][0]['message']['type'];
        $userMessage = $events['events'][0]['message']['text'];     
        $idMessage = $events['events'][0]['message']['id'];             
    }
    if(isset($events['events'][0]) && array_key_exists('postback',$events['events'][0])){
        $is_postback = true;
        $dataPostback = NULL;
        parse_str($events['events'][0]['postback']['data'],$dataPostback);;
        $paramPostback = NULL;
        if(array_key_exists('params',$events['events'][0]['postback'])){
            if(array_key_exists('date',$events['events'][0]['postback']['params'])){
                $paramPostback = $events['events'][0]['postback']['params']['date'];
            }
            if(array_key_exists('time',$events['events'][0]['postback']['params'])){
                $paramPostback = $events['events'][0]['postback']['params']['time'];
            }
            if(array_key_exists('datetime',$events['events'][0]['postback']['params'])){
                $paramPostback = $events['events'][0]['postback']['params']['datetime'];
            }                       
        }
    }   
    if(!is_null($is_postback)){
        $textReplyMessage = "ข้อความจาก Postback Event Data = ";
        if(is_array($dataPostback)){
            $textReplyMessage.= json_encode($dataPostback);
        }
        if(!is_null($paramPostback)){
            $textReplyMessage.= " \r\nParams = ".$paramPostback;
        }
        $replyData = new TextMessageBuilder($textReplyMessage);     
    }
    if(!is_null($is_message)){
        switch ($typeMessage){
            case 'text':
                $userMessage = strtolower($userMessage); // แปลงเป็นตัวเล็ก สำหรับทดสอบ
                switch ($userMessage) {
                    case "hello":
                        /*
                        $textReplyMessage = "Bot ตอบกลับคุณเป็นข้อความ";
                        $replyData = new TextMessageBuilder($textReplyMessage);
                        break;
                        */
                        $userProfile = $bot->getProfile($userID);
                        $userData = $userProfile->getJSONDecodedBody(); // return array 

                        $textReplyMessage = "สวัสดีครับ คุณ " . $userData['displayName']; // $userID;
                        $replyData = new TextMessageBuilder($textReplyMessage);
                        break;
                    case "register":
                            /*
                            $textReplyMessage = "Bot ตอบกลับคุณเป็นข้อความ";
                            $replyData = new TextMessageBuilder($textReplyMessage);
                            break;
                            */
                            $userProfile = $bot->getProfile($userID);
                            $userData = $userProfile->getJSONDecodedBody(); // return array 
    
                            $textReplyMessage = "สวัสดีครับ คุณ " . $userData['displayName'] . " กรุณากรอกชื่อ-สกุล และหมายเลขโทรศัพท์ของท่าน เพื่อยืนยันกรณีได้รับรางวัล";
                            $replyData = new TextMessageBuilder($textReplyMessage);
                            break;
                    case "uploadimage":
                            $textReplyMessage = "กรุณาเลือกรูปที่ต้องการ\n\nเงื่อนไข: ผู้ร่วมกิจกรรมต้องถ่ายรูปตัวเองกับปั๊มที่เช็กอิน และอัพโหลดรูปเข้าระบบ การเช็กอินจึงจะสมบูรณ์";
                            $replyData = new TextMessageBuilder($textReplyMessage);
                            break;
                    case "uploadreceipt":
                                $textReplyMessage = "กรุณาเลือกรูปใบเสร็จที่ต้องการ\n\nเงื่อนไข: ผู้ร่วมกิจกรรมต้องสะสมใบเสร็จหรือสลิปบัตรสมาชิกบางจาก จากการเติมน้ำมันบางจากตั้งแต่ 2,000 บาทขึ้นไป และอัพโหลดเข้าระบบ";
                                $replyData = new TextMessageBuilder($textReplyMessage);
                                break;
                    case "i":
                        $picFullSize = 'https://www.mywebsite.com/imgsrc/photos/f/simpleflower';
                        $picThumbnail = 'https://www.mywebsite.com/imgsrc/photos/f/simpleflower/240';
                        $replyData = new ImageMessageBuilder($picFullSize,$picThumbnail);
                        break;                    
                    case "m":
                        $textReplyMessage = "Bot ตอบกลับคุณเป็นข้อความ";
                        $textMessage = new TextMessageBuilder($textReplyMessage);
                                         
                        $picFullSize = 'https://www.mywebsite.com/imgsrc/photos/f/simpleflower';
                        $picThumbnail = 'https://www.mywebsite.com/imgsrc/photos/f/simpleflower/240';
                        $imageMessage = new ImageMessageBuilder($picFullSize,$picThumbnail);
                                         
                        $placeName = "ที่ตั้งร้าน";
                        $placeAddress = "แขวง พลับพลา เขต วังทองหลาง กรุงเทพมหานคร ประเทศไทย";
                        $latitude = 13.780401863217657;
                        $longitude = 100.61141967773438;
                        $locationMessage = new LocationMessageBuilder($placeName, $placeAddress, $latitude ,$longitude);        
     
                        $multiMessage =     new MultiMessageBuilder;
                        $multiMessage->add($textMessage);
                        $multiMessage->add($imageMessage);
                        $multiMessage->add($locationMessage);
                        $replyData = $multiMessage;                                     
                        break;
                        /*
                        case "t_c":
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
                                new PostbackTemplateActionBuilder(
                                    'Postback', // ข้อความแสดงในปุ่ม
                                    http_build_query(array(
                                        'action'=>'buy',
                                        'item'=>100
                                    )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                    'Postback Text'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                ),      
                            );
                            $replyData = new TemplateMessageBuilder('Carousel',
                                new CarouselTemplateBuilder(
                                    array(
                                        new CarouselColumnTemplateBuilder(
                                            'Title Carousel',
                                            'Description Carousel',
                                            'https://www.mywebsite.com/imgsrc/photos/f/sampleimage/700',
                                            $actionBuilder
                                        ),
                                        new CarouselColumnTemplateBuilder(
                                            'Title Carousel',
                                            'Description Carousel',
                                            'https://www.mywebsite.com/imgsrc/photos/f/sampleimage/700',
                                            $actionBuilder
                                        ),
                                        new CarouselColumnTemplateBuilder(
                                            'Title Carousel',
                                            'Description Carousel',
                                            'https://www.mywebsite.com/imgsrc/photos/f/sampleimage/700',
                                            $actionBuilder
                                        ),                                          
                                    )
                                )
                            );
                            break;  
                         */
                        case "whatis":
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
                                new PostbackTemplateActionBuilder(
                                    'Postback', // ข้อความแสดงในปุ่ม
                                    http_build_query(array(
                                        'action'=>'buy',
                                        'item'=>100
                                    )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                    'Postback Text'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                ),      
                            );
                            $replyData = new TemplateMessageBuilder('Carousel',
                                new CarouselTemplateBuilder(
                                    array(
                                        new CarouselColumnTemplateBuilder(
                                            'Step 1',
                                            'Description Carousel',
                                            'https://www.mywebsite.com/imgsrc/photos/f/sampleimage/700',
                                            $actionBuilder
                                        ),
                                        new CarouselColumnTemplateBuilder(
                                            'Step 2',
                                            'Description Carousel',
                                            'https://www.mywebsite.com/imgsrc/photos/f/sampleimage/700',
                                            $actionBuilder
                                        ),
                                        new CarouselColumnTemplateBuilder(
                                            'Step 3',
                                            'Description Carousel',
                                            'https://www.mywebsite.com/imgsrc/photos/f/sampleimage/700',
                                            $actionBuilder
                                        ),   
                                        new CarouselColumnTemplateBuilder(
                                            'Step 4',
                                            'Description Carousel',
                                            'https://www.mywebsite.com/imgsrc/photos/f/sampleimage/700',
                                            $actionBuilder
                                        ),                                        
                                    )
                                )
                            );
                            break;      
                    default:
                        $textReplyMessage = "ข้อมูลที่คุณกรอกไม่ถูกต้อง";
                        $replyData = new TextMessageBuilder($textReplyMessage);         
                        break;                                      
                }
                break;
            case (preg_match('/^image/',$typeMessage) ? true : false) :
                    $response = $bot->getMessageContent($idMessage);
                    if ($response->isSucceeded()) {
                        // คำสั่ง getRawBody() ในกรณีนี้ จะได้ข้อมูลส่งกลับมาเป็น binary 
                        // เราสามารถเอาข้อมูลไปบันทึกเป็นไฟล์ได้
                        $dataBinary = $response->getRawBody(); // return binary
                        // ดึงข้อมูลประเภทของไฟล์ จาก header
                        $fileType = $response->getHeader('Content-Type');    
                        switch ($fileType){
                            case (preg_match('/^image/',$fileType) ? true : false):
                                list($typeFile,$ext) = explode("/",$fileType);
                                $ext = ($ext=='jpeg' || $ext=='jpg')?"jpg":$ext;
                                $fileNameSave = "img_" . date('Ymd_his_'). rand(10000,99999) .".".$ext;
                                break;                                                                            
                        }
                        /*
                        $botDataFolder = USER_IMAGE_FOLDER . '/'; // โฟลเดอร์หลักที่จะบันทึกไฟล์
                        $botDataUserFolder = $botDataFolder.$userID; // มีโฟลเดอร์ด้านในเป็น userId อีกขั้น
                        if(!file_exists($botDataUserFolder)) { // ตรวจสอบถ้ายังไม่มีให้สร้างโฟลเดอร์ userId
                            mkdir($botDataUserFolder, 0777, true);
                        }   
                        // กำหนด path ของไฟล์ที่จะบันทึก
                        $fileFullSavePath = $botDataUserFolder.'/'.$fileNameSave;
                        file_put_contents($fileFullSavePath,$dataBinary); // ทำการบันทึกไฟล์
                        $textReplyMessage = "บันทึกไฟล์เรียบร้อยแล้ว";//. 'https://sdr-lineoa-php.herokuapp.com/' . $fileFullSavePath;
                        $replyData = new TextMessageBuilder($textReplyMessage);
                        break;
                        */                     
                        $url = 'https://bcpcheckin.bangchak.co.th/bcpcheckin/saveimage.php';
                        $data = array(
                                'userID' => $userID, 
                                'sID' => $sID,
                                'imagedata' => $dataBinary                                
                            );
                        
                        // use key 'http' even if you send the request to https://...
                        $options = array(
                            'http' => array(
                                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                                'method'  => 'POST',
                                'content' => http_build_query($data)
                            )
                        );
                        $context  = stream_context_create($options);
                        $result = file_get_contents($url, false, $context);
                        
                        //$textReplyMessage = "บันทึกไฟล์เรียบร้อยแล้ว";//. 'https://sdr-lineoa-php.herokuapp.com/' . $fileFullSavePath;
                        $textReplyMessage = $result;//. 'https://sdr-lineoa-php.herokuapp.com/' . $fileFullSavePath;
                        $replyData = new TextMessageBuilder($textReplyMessage);
                        break;
                                               
                    }
                    $failMessage = json_encode($idMessage.' '.$response->getHTTPStatus() . ' ' . $response->getRawBody());
                    $replyData = new TextMessageBuilder($failMessage);  
                    break;  
            default:
                $textReplyMessage = json_encode($events);
                $replyData = new TextMessageBuilder($textReplyMessage);         
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