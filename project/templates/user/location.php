<?php  
    include 'header.php';

 ?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>About</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
    </head>
    <body>
    <div style="margin-left: 75px;">
        <div>
        <h1>About</h1>
        <p>We’re a quiet friendly oasis where you can relax and read a book or meet friends. We’ve always a homemade cake or two for you to try. Our sandwiches are freshly made. Our sausage rolls are just baked. We’ve often some fabulous seasonal ‘specials’ as well as our set menu. We offer takeaway too.  </p>
        </div>
        <br>
        <br>
        <br>
        <h1> Our Location </h1>
        <article></article>
        <script>
            function success(position) {
  var mapcanvas = document.createElement('div');
  mapcanvas.id = 'mapcontainer';
  mapcanvas.style.height = '400px';
  mapcanvas.style.width = '600px';

  document.querySelector('article').appendChild(mapcanvas);

  var coords = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
  
  var options = {
    zoom: 15,
    center: coords,
    mapTypeControl: false,
    navigationControlOptions: {
      style: google.maps.NavigationControlStyle.SMALL
    },
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  var map = new google.maps.Map(document.getElementById("mapcontainer"), options);

  var marker = new google.maps.Marker({
      position: coords,
      map: map,
      title:"You are here!"
  });
}

if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(success);
} else {
  error('Geo Location is not supported');
}

            
            
            </script>
    </div>        
    </body>

</html>
