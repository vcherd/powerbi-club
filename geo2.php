<html>
<body>
<p id="demo">Click the button to get your position:</p>
<button onclick="getLocation()">Try It</button>
<div id="mapholder"></div>
<script>
var x=document.getElementById("demo");
function getLocation()   {  if (navigator.geolocation)
    {    navigator.geolocation.getCurrentPosition(showPosition,showError);    }
  else{x.innerHTML="Geolocation is not supported by this browser.";}  }

function showPosition(position)
  {  var latlon=position.coords.latitude+","+position.coords.longitude;
  var img_url="http://maps.googleapis.com/maps/api/staticmap?center="
  +latlon+"&zoom=14&size=400x300&sensor=false";
  document.getElementById("mapholder").innerHTML="<img src='"+img_url+"'>";  }

</script>
</body>
</html>