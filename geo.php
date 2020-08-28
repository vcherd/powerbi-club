<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Get Visitor's Location Using HTML5 Geolocation</title>
<script>
    function showPosition() {
        
        var arr_Destination = [
            {title:'MTower',lat:13.694827,lng:100.606188},
            {title:'Paragon',lat:13.7477777029,lng:100.534815},
        /*  {title:'Place C',lat:ddddd,lng:ddddd},
            {title:'Place D',lat:ddddd,lng:ddddd},
            {title:'Place E',lat:ddddd,lng:ddddd},
            {title:'Place F',lat:ddddd,lng:ddddd},*/
        ];

        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var positionInfo = "Your current position is (" + "Latitude: " + position.coords.latitude + ", " + "Longitude: " + position.coords.longitude + ")";
                document.getElementById("result").innerHTML = positionInfo;

            });

        } else {
            alert("Sorry, your browser does not support HTML5 geolocation.");
        }
    }

</script>
</head>
<body>
    <div id="result">
        <!--Position information will be inserted here-->
    </div>
    <button type="button" onclick="showPosition();">Show Position</button>
</body>
</html>