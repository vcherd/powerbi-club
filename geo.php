<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Check-in</title>
<script>
    function showPosition() {
        
        var arr_Destination = [
            {title:'M-Tower',lat:13.694827,lng:100.606188},
            {title:'Paragon',lat:13.7477777029,lng:100.534815},
            {title:'BCP Refinery',lat:13.685400,lng:100.599556},
            {title:'BTS Punnawithi',lat:13.689278,lng:100.608922},
        
        ];

        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var positionInfo = "Your current position is (" + "Latitude: " + position.coords.latitude + ", " + "Longitude: " + position.coords.longitude + ")";
                
                for( i = 0;i<arr_Destination.length;i++){ 
                    var userdistance = distance(position.coords.latitude,position.coords.longitude,arr_Destination[i].lat,arr_Destination[i].lng,"K");
                    positionInfo = positionInfo + "<BR>" + arr_Destination[i].title + " = " + userdistance;

                    if (userdistance <= 0.4) {
                        //positionInfo = positionInfo + " => Check-in";
                        document.getElementById('userLoc').value = arr_Destination[i].title;
                        //document.getElementById('checkInFM').Submit();
                    }
                }
                
                document.getElementById("result").innerHTML = positionInfo;
            });

        } else {
            alert("Sorry, your browser does not support HTML5 geolocation.");
        }
    }

    function distance(lat1, lon1, lat2, lon2, unit) {
        var radlat1 = Math.PI * lat1/180
        var radlat2 = Math.PI * lat2/180
        var radlon1 = Math.PI * lon1/180
        var radlon2 = Math.PI * lon2/180
        var theta = lon1-lon2
        var radtheta = Math.PI * theta/180
        var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
        dist = Math.acos(dist)
        dist = dist * 180/Math.PI
        dist = dist * 60 * 1.1515
        if (unit=="K") { dist = dist * 1.609344 }
        if (unit=="N") { dist = dist * 0.8684 }
    return dist
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
    <form id="checkInFm" method="POST" action="poi-checkin.php">
    <input id="userLoc" name="userLoc" type="text">
    <button id="SubmitBtn" type="submit">
    </form>
</body>
</html>