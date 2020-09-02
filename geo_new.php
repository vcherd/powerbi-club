<?php
require_once './config/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Check-in</title>
<script>
    function showPosition() {
        
       
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                
                
                //for( i = 0;i<arr_Destination.length;i++){ 
                //    var userdistance = distance(position.coords.latitude,position.coords.longitude,arr_Destination[i].lat,arr_Destination[i].lng,"K");
                    
                   
                        //positionInfo = positionInfo + " => Check-in";
                    if (position.coords.latitude > 0) && (position.coords.longitude > 0)    
                        document.getElementById('latitude').value = position.coords.latitude;
                        document.getElementById('longitude').value = position.coords.longitude;
                        document.checkInFm.submit();
                    }
                }
                
                if (found == false) {
                    positionInfo = "No nearby service station around you. Please get closer, thank you."
                }
                document.getElementById("result").innerHTML = positionInfo;
            });

        } else {
            alert("Sorry, your browser does not support HTML5 geolocation.");
        }
    }

window.onload = function(){
    //document.getElementById('autoClickBtn').click();
    showPosition();
}
</script>
</head>
<body>
    <div id="result">
        <!--Position information will be inserted here-->
    </div>
    <!--<button id="autoClickBtn" onclick="showPosition();">Show Position</button>-->
    <form id="checkInFm" name="checkInFm" method="POST" action="poi_checkin_new.php">
    <input id="latitude" name="latitude" type="hidden">
    <input id="longitude" name="longitude" type="hidden">
    <input id="userID" name="userID" type="hidden" value="<?php echo $_GET["userID"];?>">
    <!--<button id="SubmitBtn" type="submit">Submit</button> -->
    </form>
</body>
</html>