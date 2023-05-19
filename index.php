<!DOCTYPE html>
<html>
    <head>
        <title>Lat & Long Retrieval From Address</title>
    </head>
<body>

<h2>Retrieve and display coordinates (longitude and latitude) for an address, using Google Maps and OpenStreetMap (OSM). Show results from both Google Maps and OSM.</h2>


<form method="post" novalidate="novalidate">
   
    <input type="text" name="street-address" value="Bangalore" size="40" id="address" aria-required="true" aria-invalid="false" placeholder="Address"/>
    <input type="text" id="lat" value="" placeholder="Latitude"><input type="text" id="long" value="" placeholder="Longitude">
    <input type="hidden" name="addressField1" value=""><input type="hidden" name="postcodeField" >
    <a href="#" id="find-address" title="Find Address" class="button">Find Lat & Lang</a>

    <br>
    <h2>OSM Lat and Lon</h2>
    <input type="text" id="lat1" value="" placeholder="Latitude From OSM"><input type="text" id="long1" value="" placeholder="Longitude From OSM">
</form>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
<script>
jQuery(document).ready(function($) {
  $('#find-address').click(function() {
    var address = $('#address').val();
    if(address == ''){
        alert("Enter Address");
        $('#address').focus();
        return false;
    }
    var addressClean = address.replace(/\s+/g, '+');
    $.ajax('Latlon.php', {
				type: 'POST',  // http method
        dataType: 'json',
        data:{addressClean: addressClean},
				success: function (value, status, xhr) {
          $("#lat").val(value.lat);
          $("#long").val(value.long); 
          $("#lat1").val(value.osmlat);
          $("#long1").val(value.osmlong);
				},
				error: function (jqXhr, textStatus, errorMessage) {
                    alert(errorMessage);
					}
			});
  });
})
</script>
</body>
</html>

