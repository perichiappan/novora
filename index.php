<!DOCTYPE html>
<html>
    <head>
        <style>
            #map {
            height: 100%;
            }
            /* Optional: Makes the sample page fill the window. */
            html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            }
        </style>
    </head>
<body>

<h2>Retrieve and display coordinates (longitude and latitude) for an address, using Google Maps and OpenStreetMap (OSM). Show results from both Google Maps and OSM.</h2>


<form method="post" novalidate="novalidate">
   
    <input type="text" name="street-address" value="Bangalore" size="40" id="address" aria-required="true" aria-invalid="false" placeholder="Address"/>
    <input type="text" id="lat" value="" placeholder="Latitude"><input type="text" id="long" value="" placeholder="Longitude">
    <input type="hidden" name="addressField1" value=""><input type="hidden" name="postcodeField" >
    <a href="#" id="find-address" title="Find Address" class="button">Find Lat & Lang</a>

    <div id="map-embed">
        <div id="map-embed-div" style="height:400px;width:100%;"></div>
    </div>
    <br>
    <h2>OSM Lat and Lon</h2>
    <input type="text" id="lat1" value="" placeholder="Latitude From OSM"><input type="text" id="long1" value="" placeholder="Longitude From OSM">
</form>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDwtLJ8ditOU1vrNL-v5ofV8IoSD30gNuI" async defer></script>
<script>
    var apiKey = 'AIzaSyDwtLJ8ditOU1vrNL-v5ofV8IoSD30gNuI';
var longitude, latitude, map;

jQuery(document).ready(function($) {
  $('#find-address').click(function() {
    var address = $('#address').val();
    if(address == ''){
        alert("Enter Address");
        $('#address').focus();
        return false;
    }
    var addressClean = address.replace(/\s+/g, '+');
    var apiCall = 'https://maps.googleapis.com/maps/api/geocode/json?address=' + addressClean + '&key=' + apiKey + '';
   
    //$.getJSON(apiCall,function (data, textStatus) {
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({
      address: addressClean + ","
    }, function(results, status) {
      console.log(status);
      if (status == 'OK') {

        console.log(results);
        longitude = results[0].geometry.location.lng();
        latitude = results[0].geometry.location.lat();
        $("#long").val(longitude);
        $("#lat").val(latitude);
        // geocoder is asynchronous, do this in the callback function
        longitude = $("input#long").val();
        latitude = $("input#lat").val();

        if (longitude && latitude) {
          longitude = parseFloat(longitude);
          latitude = parseFloat(latitude);

          initMap(longitude, latitude);
        }
      } else alert("geocode failed")
    });
    /*OSM API CALL */
     /* OSM API CALL */
     var apiCallOSM = 'https://nominatim.openstreetmap.org/search?q=' + addressClean + '&format=json&polygon=1&addressdetails=1'
     $.ajax(apiCallOSM, {
				type: 'POST',  // http method
                dataType: 'json',
				success: function (data, status, xhr) {
                    //alert(status);
                    $.each(data,function(id,value) {
                        $("#lat1").val(value.lat);
                        $("#long1").val(value.lon); 
                });
				},
				error: function (jqXhr, textStatus, errorMessage) {
                    alert(errorMessage);
					}
			});
  });

  function initMap(longitude, latitude) {
    var myLatlng = new google.maps.LatLng(latitude, longitude);

    var mapOptions = {
      zoom: 12,
      center: myLatlng
    }

    var map = new google.maps.Map(document.getElementById("map-embed-div"), mapOptions);

    var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      draggable: true,
      title: "Address"
    });
  };
})
</script>
</body>
</html>

