<?php
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
use LINE\LINEBot\QuickReplyBuilder;
use LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder;
use LINE\LINEBot\QuickReplyBuilder\ButtonBuilder\QuickReplyButtonBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraRollTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\LocationTemplateActionBuilder;
use LINE\LINEBot\RichMenuBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuSizeBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder;
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
use LINE\LINEBot\Constant\Flex\BubleContainerSize;
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
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SpanComponentBuilder;
 
 
$is_carousel = NULL;
$is_bubble = NULL;
$is_singleBubble = NULL;
$count_container = 0;
$count_bubble = 0;
$name_container = NULL;
 
$bubble_direction = array();
$bubble_size = array();
$bubble_action = array();
$block_header = array();
$block_hero = array();
$block_body = array();
$block_footer = array();
$block_header_style = array();
$block_hero_style = array();
$block_body_style = array();
$block_footer_style = array();
$box_header = array();
$box_hero = array();
$box_body = array();
$box_footer = array();
 
function bubbleContainerAttr($xmlObj){
    $bubbleContainer = $xmlObj;
    $bubbleContainerAttr = array(null,null,null);
    foreach($bubbleContainer->attributes() as $key=>$val){
        if($key=="direction"){$bubbleContainerAttr[0]=implode("",(array)$val[0]);}
        if($key=="size"){$bubbleContainerAttr[1]=implode("",(array)$val[0]);}
        if($key=="action"){
            $textButton = "u";
            $strAction = str_replace(")","",implode("",(array)$val[0]));
            switch($strAction){
                case (preg_match('/^p\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new PostbackTemplateActionBuilder($textButton,$data);              
                    break;
                case (preg_match('/^m\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new MessageTemplateActionBuilder($textButton,$data);               
                    break;
                case (preg_match('/^u\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,$data);           
                    break;
                case (preg_match('/^c\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/camera/");     
                    break;
                case (preg_match('/^cs\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/cameraRoll/single/");  
                    break;
                case (preg_match('/^cm\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/cameraRoll/multi/");               
                    break;
                case (preg_match('/^l\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/location");                
                    break;
                case (preg_match('/^d\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new DatetimePickerTemplateActionBuilder($textButton,$data,'datetime');
                    break;                                                                                                                                          
                default:
                    $actionVal = new PostbackTemplateActionBuilder($textButton,""); 
                    break;                  
            }                   
            $bubbleContainerAttr[2] = $actionVal;
        }       
    }   
    return $bubbleContainerAttr;            
}
function blockStyleAttr($xmlObj){
    $blockStyle = $xmlObj;
    $blockStyleAttr = array(null,null,null);
    foreach($blockStyle->attributes() as $key=>$val){
        if($key=="backgroundColor"){$blockStyleAttr[0]=implode("",(array)$val[0]);}
        if($key=="separator"){$blockStyleAttr[1]=(boolean)implode("",(array)$val[0]);}
        if($key=="separatorColor"){$blockStyleAttr[2]=implode("",(array)$val[0]);}
    }   
    if(count($blockStyle->attributes())>0){
        return $blockStyleAttr;         
    }else{
        return NULL;    
    }
}
function textComponentArr($xmlObj){
    $textComponent = $xmlObj;
    $textComponentAttr = array(trim((string)$textComponent),null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
    foreach($textComponent->attributes() as $key=>$val){
        if($key=="flex"){$textComponentAttr[1]=(int)implode("",(array)$val[0]);}
        if($key=="margin"){$textComponentAttr[2]=implode("",(array)$val[0]);}
        if($key=="size"){$textComponentAttr[3]=implode("",(array)$val[0]);}
        if($key=="align"){$textComponentAttr[4]=implode("",(array)$val[0]);}
        if($key=="gravity"){$textComponentAttr[5]=implode("",(array)$val[0]);}
        if($key=="wrap"){$textComponentAttr[6]=(boolean)implode("",(array)$val[0]);}
        if($key=="maxLines"){$textComponentAttr[7]=(int)implode("",(array)$val[0]);}
        if($key=="weight"){$textComponentAttr[8]=implode("",(array)$val[0]);}       
        if($key=="color"){$textComponentAttr[9]=implode("",(array)$val[0]);}
        if($key=="action"){
            $textButton = "u";
            $strAction = str_replace(")","",implode("",(array)$val[0]));
            switch($strAction){
                case (preg_match('/^p\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new PostbackTemplateActionBuilder($textButton,$data);              
                    break;
                case (preg_match('/^m\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new MessageTemplateActionBuilder($textButton,$data);               
                    break;
                case (preg_match('/^u\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,$data);           
                    break;
                case (preg_match('/^c\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/camera/");     
                    break;
                case (preg_match('/^cs\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/cameraRoll/single/");  
                    break;
                case (preg_match('/^cm\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/cameraRoll/multi/");               
                    break;
                case (preg_match('/^l\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/location");                
                    break;
                case (preg_match('/^d\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new DatetimePickerTemplateActionBuilder($textButton,$data,'datetime');
                    break;                                                                                                                                          
                default:
                    $actionVal = new PostbackTemplateActionBuilder($textButton,""); 
                    break;                  
            }                   
            $textComponentAttr[10] = $actionVal;
        }
        if($key=="position"){$textComponentAttr[11]=implode("",(array)$val[0]);}
        if($key=="offsetTop"){$textComponentAttr[12]=implode("",(array)$val[0]);}
        if($key=="offsetBottom"){$textComponentAttr[13]=implode("",(array)$val[0]);}
        if($key=="offsetStart"){$textComponentAttr[14]=implode("",(array)$val[0]);}
        if($key=="offsetEnd"){$textComponentAttr[15]=implode("",(array)$val[0]);}
        if($key=="style"){$textComponentAttr[16]=implode("",(array)$val[0]);}
        if($key=="decoration"){$textComponentAttr[17]=implode("",(array)$val[0]);}
    }   
    return $textComponentAttr;
}
function boxComponentArr($xmlObj,$arrComponent = array()){
    $boxComponent = $xmlObj;
    $boxComponentAttr = array(null,$arrComponent,null,null,null,null,null,null,null,null,null,null,null,null
    ,null,null,null,null,null,null,null,null);
    foreach($boxComponent->attributes() as $key=>$val){
        if($key=="layout"){$boxComponentAttr[0]=implode("",(array)$val[0]);}
        if($key=="flex"){$boxComponentAttr[2]=(int)implode("",(array)$val[0]);}
        if($key=="spacing"){$boxComponentAttr[3]=implode("",(array)$val[0]);}
        if($key=="margin"){$boxComponentAttr[4]=implode("",(array)$val[0]);}
        if($key=="action"){
            $textButton = "u";
            $strAction = str_replace(")","",implode("",(array)$val[0]));
            switch($strAction){
                case (preg_match('/^p\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new PostbackTemplateActionBuilder($textButton,$data);              
                    break;
                case (preg_match('/^m\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new MessageTemplateActionBuilder($textButton,$data);               
                    break;
                case (preg_match('/^u\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,$data);           
                    break;
                case (preg_match('/^c\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/camera/");     
                    break;
                case (preg_match('/^cs\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/cameraRoll/single/");  
                    break;
                case (preg_match('/^cm\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/cameraRoll/multi/");               
                    break;
                case (preg_match('/^l\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/location");                
                    break;
                case (preg_match('/^d\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new DatetimePickerTemplateActionBuilder($textButton,$data,'datetime');
                    break;                                                                                                                                          
                default:
                    $actionVal = new PostbackTemplateActionBuilder($textButton,""); 
                    break;                  
            }       
            $boxComponentAttr[5] = $actionVal;
        }
        if($key=="paddingAll"){$boxComponentAttr[6]=implode("",(array)$val[0]);}
        if($key=="paddingTop"){$boxComponentAttr[7]=implode("",(array)$val[0]);}
        if($key=="paddingBottom"){$boxComponentAttr[8]=implode("",(array)$val[0]);}
        if($key=="paddingStart"){$boxComponentAttr[9]=implode("",(array)$val[0]);}
        if($key=="paddingEnd"){$boxComponentAttr[10]=implode("",(array)$val[0]);}
        if($key=="backgroundColor"){$boxComponentAttr[11]=implode("",(array)$val[0]);}
        if($key=="borderColor"){$boxComponentAttr[12]=implode("",(array)$val[0]);}
        if($key=="borderWidth"){$boxComponentAttr[13]=implode("",(array)$val[0]);}
        if($key=="cornerRadius"){$boxComponentAttr[14]=implode("",(array)$val[0]);}
        if($key=="width"){$boxComponentAttr[15]=implode("",(array)$val[0]);}
        if($key=="height"){$boxComponentAttr[16]=implode("",(array)$val[0]);}
        if($key=="position"){$boxComponentAttr[17]=implode("",(array)$val[0]);}
        if($key=="offsetTop"){$boxComponentAttr[18]=implode("",(array)$val[0]);}
        if($key=="offsetBottom"){$boxComponentAttr[19]=implode("",(array)$val[0]);}
        if($key=="offsetStart"){$boxComponentAttr[20]=implode("",(array)$val[0]);}
        if($key=="offsetEnd"){$boxComponentAttr[21]=implode("",(array)$val[0]);}
    }   
    return $boxComponentAttr;
}
function buttonComponentArr($xmlObj){
    $buttonComponent = $xmlObj;
    $textButton = trim((string)$buttonComponent);
    $buttonComponentAttr = array(new PostbackTemplateActionBuilder($textButton,"nothing"),null,null,null,
    null,null,null,null,null,null,null,null);
    foreach($buttonComponent->attributes() as $key=>$val){
        if($key=="action"){
            $strAction = str_replace(")","",implode("",(array)$val[0]));
            switch($strAction){
                case (preg_match('/^p\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new PostbackTemplateActionBuilder($textButton,$data);              
                    break;
                case (preg_match('/^m\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new MessageTemplateActionBuilder($textButton,$data);               
                    break;
                case (preg_match('/^u\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,$data);           
                    break;
                case (preg_match('/^c\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/camera/");     
                    break;
                case (preg_match('/^cs\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/cameraRoll/single/");  
                    break;
                case (preg_match('/^cm\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/cameraRoll/multi/");               
                    break;
                case (preg_match('/^l\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/location");                
                    break;
                case (preg_match('/^d\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new DatetimePickerTemplateActionBuilder($textButton,$data,'datetime');
                    break;                                                                                                                                          
                default:
                    $actionVal = new PostbackTemplateActionBuilder($textButton,"false");    
                    break;                  
            }
            $buttonComponentAttr[0] = $actionVal;
        }
        if($key=="flex"){$buttonComponentAttr[1]=(int)implode("",(array)$val[0]);}
        if($key=="margin"){$buttonComponentAttr[2]=implode("",(array)$val[0]);}
        if($key=="height"){$buttonComponentAttr[3]=implode("",(array)$val[0]);}
        if($key=="style"){$buttonComponentAttr[4]=implode("",(array)$val[0]);}
        if($key=="color"){$buttonComponentAttr[5]=implode("",(array)$val[0]);}
        if($key=="gravity"){$buttonComponentAttr[6]=implode("",(array)$val[0]);}
         
        if($key=="position"){$buttonComponentAttr[7]=implode("",(array)$val[0]);}
        if($key=="offsetTop"){$buttonComponentAttr[8]=implode("",(array)$val[0]);}
        if($key=="offsetBottom"){$buttonComponentAttr[9]=implode("",(array)$val[0]);}
        if($key=="offsetStart"){$buttonComponentAttr[10]=implode("",(array)$val[0]);}
        if($key=="offsetEnd"){$buttonComponentAttr[11]=implode("",(array)$val[0]);}     
         
    }   
    return $buttonComponentAttr;
}
function iconComponentAttr($xmlObj){
    $iconComponent = $xmlObj;
    $iconComponentAttr = array(null,null,null,null,null,null,null,null,null);
    foreach($iconComponent->attributes() as $key=>$val){
        if($key=="url"){$iconComponentAttr[0]=implode("",(array)$val[0]);}
        if($key=="margin"){$iconComponentAttr[1]=implode("",(array)$val[0]);}
        if($key=="size"){$iconComponentAttr[2]=implode("",(array)$val[0]);}
        if($key=="aspectRatio"){$iconComponentAttr[3]=implode("",(array)$val[0]);}
         
        if($key=="position"){$iconComponentAttr[4]=implode("",(array)$val[0]);}
        if($key=="offsetTop"){$iconComponentAttr[5]=implode("",(array)$val[0]);}
        if($key=="offsetBottom"){$iconComponentAttr[6]=implode("",(array)$val[0]);}
        if($key=="offsetStart"){$iconComponentAttr[7]=implode("",(array)$val[0]);}
        if($key=="offsetEnd"){$iconComponentAttr[8]=implode("",(array)$val[0]);}                
         
    }   
    return $iconComponentAttr;      
}
function spanComponentAttr($xmlObj){
    $spanComponent = $xmlObj;
    $spanComponentAttr = array("test span",null,null,null,null,null);   
    foreach($spanComponent->attributes() as $key=>$val){
        if($key=="size"){$spanComponentAttr[1]=implode("",(array)$val[0]);}
        if($key=="color"){$spanComponentAttr[2]=implode("",(array)$val[0]);}        
        if($key=="weight"){$spanComponentAttr[3]=implode("",(array)$val[0]);}
        if($key=="style"){$spanComponentAttr[4]=implode("",(array)$val[0]);}
        if($key=="decoration"){$spanComponentAttr[5]=implode("",(array)$val[0]);}       
    }   
    return $spanComponentAttr;      
}
function separatorComponentAttr($xmlObj){
    $separatorComponent = $xmlObj;
    $separatorComponentAttr = array();
    foreach($separatorComponent->attributes() as $key=>$val){
        if($key=="margin"){$separatorComponentAttr[0]=implode("",(array)$val[0]);}
        if($key=="color"){$separatorComponentAttr[1]=implode("",(array)$val[0]);}
    }   
    return $separatorComponentAttr;     
}
function fillerComponentAttr($xmlObj){
    $fillerComponent= $xmlObj;
    $fillerComponentAttr = array();
    foreach($fillerComponent->attributes() as $key=>$val){
        if($key=="flex"){$fillerComponentAttr[0]=(int)implode("",(array)$val[0]);}
    }   
    return $fillerComponentAttr;                
}
function spacerComponentAttr($xmlObj){
    $spacerComponent = $xmlObj;
    $spacerComponentAttr = array("md");
    foreach($spacerComponent->attributes() as $key=>$val){
        if($key=="size"){$spacerComponentAttr[0]=implode("",(array)$val[0]);}
    }   
    return $spacerComponentAttr;    
}
function imageComponentArr($xmlObj){
    $imageComponent = $xmlObj;
    $imageComponentAttr = array(null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
    foreach($imageComponent->attributes() as $key=>$val){
        if($key=="url"){$imageComponentAttr[0]=implode("",(array)$val[0]);}
        if($key=="flex"){$imageComponentAttr[1]=(int)implode("",(array)$val[0]);}
        if($key=="margin"){$imageComponentAttr[2]=implode("",(array)$val[0]);}
        if($key=="align"){$imageComponentAttr[3]=implode("",(array)$val[0]);}
        if($key=="gravity"){$imageComponentAttr[4]=implode("",(array)$val[0]);}
        if($key=="size"){$imageComponentAttr[5]=implode("",(array)$val[0]);}
        if($key=="aspectRatio"){$imageComponentAttr[6]=implode("",(array)$val[0]);}
        if($key=="aspectMode"){$imageComponentAttr[7]=implode("",(array)$val[0]);}      
        if($key=="backgroundColor"){$imageComponentAttr[8]=implode("",(array)$val[0]);}
        if($key=="action"){
            $textButton = "u";
            $strAction = str_replace(")","",implode("",(array)$val[0]));
            switch($strAction){
                case (preg_match('/^p\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new PostbackTemplateActionBuilder($textButton,$data);              
                    break;
                case (preg_match('/^m\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new MessageTemplateActionBuilder($textButton,$data);               
                    break;
                case (preg_match('/^u\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,$data);           
                    break;
                case (preg_match('/^c\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/camera/");     
                    break;
                case (preg_match('/^cs\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/cameraRoll/single/");  
                    break;
                case (preg_match('/^cm\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/cameraRoll/multi/");               
                    break;
                case (preg_match('/^l\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new UriTemplateActionBuilder($textButton,"line://nv/location");                
                    break;
                case (preg_match('/^d\(/',$strAction) ? true : false):
                    list($ac,$data) = explode("(",$strAction);
                    $actionVal = new DatetimePickerTemplateActionBuilder($textButton,$data,'datetime');
                    break;                                                                                                                                          
                default:
                    $actionVal = new PostbackTemplateActionBuilder($textButton,""); 
                    break;                  
            }
            $imageComponentAttr[9] = $actionVal;            
        }
        if($key=="position"){$imageComponentAttr[10]=implode("",(array)$val[0]);}
        if($key=="offsetTop"){$imageComponentAttr[11]=implode("",(array)$val[0]);}
        if($key=="offsetBottom"){$imageComponentAttr[12]=implode("",(array)$val[0]);}
        if($key=="offsetStart"){$imageComponentAttr[13]=implode("",(array)$val[0]);}
        if($key=="offsetEnd"){$imageComponentAttr[14]=implode("",(array)$val[0]);}              
    }   
    return $imageComponentAttr;
}
function genComponent($elName,$childObj,$arr_Element=null){
    if($elName=="box"){
        $arr_childObj = array_chunk(boxComponentArr($childObj,listElement($childObj,$arr_Element)), 6, true); 
        $param_childObj = array_shift($arr_childObj);
        $option_attr = boxComponentArr($childObj);
        $boxObj_C = new BoxComponentBuilder(...$param_childObj);    
        if(is_array($option_attr) && count($option_attr)>0){
            $boxObj_C->setPaddingAll($option_attr[6])
            ->setPaddingTop($option_attr[7])
            ->setPaddingBottom($option_attr[8])
            ->setPaddingStart($option_attr[9])
            ->setPaddingEnd($option_attr[10])
            ->setBackgroundColor($option_attr[11])
            ->setBorderColor($option_attr[12])
            ->setBorderWidth($option_attr[13])
            ->setCornerRadius($option_attr[14])
            ->setWidth($option_attr[15])                                                                                                         
            ->setHeight($option_attr[16])
            ->setPosition($option_attr[17])
            ->setOffsetTop($option_attr[18])
            ->setOffsetBottom($option_attr[19])
            ->setOffsetStart($option_attr[20])
            ->setOffsetEnd($option_attr[21]);
        }   
        return $boxObj_C;
    }
    if($elName=="text"){
        $arr_childObj = array_chunk(textComponentArr($childObj), 11, true); 
        $param_childObj = array_shift($arr_childObj);
        $option_attr = textComponentArr($childObj);
        $textObj_C = new TextComponentBuilder(...$param_childObj); 
        if(is_array($option_attr) && count($option_attr)>0){
            $textObj_C->setPosition($option_attr[11])
            ->setOffsetTop($option_attr[12])
            ->setOffsetBottom($option_attr[13])
            ->setOffsetStart($option_attr[14])
            ->setOffsetEnd($option_attr[15])
            ->setStyle($option_attr[16])
            ->setDecoration($option_attr[17]);
        }
        $spanObj_C = array();
        foreach ($childObj->children() as $_childObj) {      
            $spanObj_C[] = new SpanComponentBuilder(...spanComponentAttr($_childObj));
        }
        $textObj_C->setContents($spanObj_C);
        return $textObj_C;
    }
    if($elName=="button"){
        $arr_childObj = array_chunk(buttonComponentArr($childObj), 7, true); 
        $param_childObj = array_shift($arr_childObj);
        $option_attr = buttonComponentArr($childObj);
        $buttonObj_C = new ButtonComponentBuilder(...$param_childObj); 
        if(is_array($option_attr) && count($option_attr)>0){
            $buttonObj_C->setPosition($option_attr[7])
            ->setOffsetTop($option_attr[8])
            ->setOffsetBottom($option_attr[9])
            ->setOffsetStart($option_attr[10])
            ->setOffsetEnd($option_attr[11]);
        }
        return $buttonObj_C;
    }
    if($elName=="icon"){
        $arr_childObj = array_chunk(iconComponentAttr($childObj), 4, true); 
        $param_childObj = array_shift($arr_childObj);
        $option_attr = iconComponentAttr($childObj);
        $iconObj_C = new IconComponentBuilder(...$param_childObj); 
        if(is_array($option_attr) && count($option_attr)>0){
            $iconObj_C->setPosition($option_attr[4])
            ->setOffsetTop($option_attr[5])
            ->setOffsetBottom($option_attr[6])
            ->setOffsetStart($option_attr[7])
            ->setOffsetEnd($option_attr[8]);
        }
        return $iconObj_C;
    }
    if($elName=="image"){
        $arr_childObj = array_chunk(imageComponentArr($childObj), 10, true); 
        $param_childObj = array_shift($arr_childObj);
        $option_attr = imageComponentArr($childObj);
        $imageObj_C = new ImageComponentBuilder(...$param_childObj); 
        if(is_array($option_attr) && count($option_attr)>0){
            $imageObj_C->setPosition($option_attr[10])
            ->setOffsetTop($option_attr[11])
            ->setOffsetBottom($option_attr[12])
            ->setOffsetStart($option_attr[13])
            ->setOffsetEnd($option_attr[14]);
        }
        return $imageObj_C;
    }           
    if($elName=="filler"){
        $fillerObj_C = new FillerComponentBuilder();    
        $option_attr = fillerComponentAttr($childObj);
        if(is_array($option_attr) && count($option_attr)>0){
            $fillerObj_C->setFlex($option_attr[0]);
        }
        return $fillerObj_C;
    }
}
function listElement($xmlObj,$arr_Element = array()){
    $arr_final = array();
    foreach ($xmlObj->children() as $childObj) {     
        $elName = $childObj->getName();  
        if($elName=="box"){
            $arr_final[] = genComponent("box",$childObj,$arr_Element);
        }else{          
            if($elName=="text"){
                $arr_final[] = genComponent("text",$childObj);
            }elseif($elName=="button"){     
                $arr_final[] = genComponent("button",$childObj);        
            }elseif($elName=="icon"){           
                $arr_final[] = genComponent("icon",$childObj);              
            }elseif($elName=="image"){          
                $arr_final[] = genComponent("image",$childObj); 
            }elseif($elName=="filler"){     
                $arr_final[] = genComponent("filler",$childObj);        
            }elseif($elName=="span"){               
                $arr_final[] = new SpanComponentBuilder(...spanComponentAttr($childObj)); //                            
            }elseif($elName=="spacer"){             
                $arr_final[] = new SpacerComponentBuilder(...spacerComponentAttr($childObj)); // 
            }elseif($elName=="separator"){              
                $arr_final[] = new SeparatorComponentBuilder(...separatorComponentAttr($childObj)); //                                      
            }else{
                $arr_final[] = array();
            }
        }
    }   
    return $arr_final;
}
function createFlex($xmlstr){
    //$objXML = new SimpleXMLElement($xmlstr);
    $xmlIterator = new SimpleXMLIterator($xmlstr);
    $xmlIterator->rewind();
    $name_container = $xmlIterator->getName();
    $count_container = $xmlIterator->count();
     
    if($name_container!="carousel"){
        $is_singleBubble = true; 
    }
    $arr_bubble = array();
    $hasBlockStyle = false;
    $i = 0;
    for( $xmlIterator; $xmlIterator->valid(); $xmlIterator->next() ) {
        $bubble_direction[$i] = NULL;
        $bubble_size[$i] = NULL;
        $bubble_action[$i] = NULL;
        $bubble_direction[$i] = bubbleContainerAttr($xmlIterator->current());
        $bubble_size[$i] = bubbleContainerAttr($xmlIterator->current());
        $bubble_action[$i] = bubbleContainerAttr($xmlIterator->current());
         
        $block_header[$i] = NULL;
        $block_hero[$i] = NULL;
        $block_body[$i] = NULL;
        $block_footer[$i] = NULL;
        $block_header_style[$i] = NULL;
        $block_hero_style[$i] = NULL;
        $block_body_style[$i] = NULL;
        $block_footer_style[$i] = NULL;
        foreach ($xmlIterator->current()->children() as $blockChild) {
            if($blockChild->getName()=="header"){
                $block_header[$i]=$blockChild;
                $block_header_style[$i] = (!is_null(blockStyleAttr($blockChild)))?new BlockStyleBuilder(...blockStyleAttr($blockChild)):NULL;   
                $box_header[$i] = listElement($blockChild->children());
            }
            if($blockChild->getName()=="hero"){
                $block_hero[$i]=$blockChild;
                $block_hero_style[$i] = (!is_null(blockStyleAttr($blockChild)))?new BlockStyleBuilder(...blockStyleAttr($blockChild)):NULL; 
                $box_hero[$i] = listElement($blockChild->children());
            }
            if($blockChild->getName()=="body"){
                $block_body[$i]=$blockChild;    
                $block_body_style[$i] = (!is_null(blockStyleAttr($blockChild)))?new BlockStyleBuilder(...blockStyleAttr($blockChild)):NULL; 
                $box_body[$i] = listElement($blockChild->children());
            }
            if($blockChild->getName()=="footer"){
                $block_footer[$i]=$blockChild;  
                $block_footer_style[$i] = (!is_null(blockStyleAttr($blockChild)))?new BlockStyleBuilder(...blockStyleAttr($blockChild)):NULL;   
                $box_footer[$i] = listElement($blockChild->children());
            }
     
        }
        $style_bubble[$i]=NULL;
        if(!is_null($block_header_style[$i]) || !is_null($block_hero_style[$i]) || !is_null($block_body_style[$i]) || !is_null($block_footer_style[$i])){
                $style_bubble[$i] = new BubbleStylesBuilder(
                    $block_header_style[$i],
                    $block_hero_style[$i],
                    $block_body_style[$i],
                    $block_footer_style[$i]
                );
        }
        $i++;
     
    }
    for($i=0;$i<$count_container;$i++){
        $containDirection = (!is_null($bubble_direction[$i]))?$bubble_direction[$i][0]:"ltr";
        $block_HeaderObj = (!is_null($block_header[$i]))?genComponent("box",$block_header[$i]->children(),$box_header[$i]):NULL;
        $block_HeroObj = (!is_null($block_hero[$i]))?genComponent("box",$block_hero[$i]->children(),$box_hero[$i]):NULL; 
        $block_BodyObj = (!is_null($block_body[$i]))?genComponent("box",$block_body[$i]->children(),$box_body[$i]):NULL;
        $block_FooterObj = (!is_null($block_footer[$i]))?genComponent("box",$block_footer[$i]->children(),$box_footer[$i]):NULL;
         
        $containHeader = $block_HeaderObj;
        $containHero = $block_HeroObj;
        $containBody = $block_BodyObj;
        $containFooter = $block_FooterObj;
        $containSize = (!is_null($bubble_size[$i]))?$bubble_size[$i][1]:NULL;
        $containAction = (!is_null($bubble_action[$i]))?$bubble_action[$i][2]:NULL;
        $bubbleObj = new BubbleContainerBuilder(
            $containDirection,$containHeader,$containHero,$containBody,$containFooter,$style_bubble[$i],$containSize
        );
        if(!is_null($containAction)){
            $bubbleObj = $bubbleObj->setAction($containAction);
        }       
        $arr_bubble[] = $bubbleObj;
    }       
    $arr_FlexMessage = NULL;
    if(!is_null($is_singleBubble)){
        $arr_FlexMessage = $arr_bubble[0];
    }else{
        $arr_FlexMessage = new CarouselContainerBuilder(
            $arr_bubble
        );
    }
    $textReplyMessage = $arr_FlexMessage;
    return $textReplyMessage;
}