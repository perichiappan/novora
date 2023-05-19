<?php
Class Curl
{
    public function processCURL($url)
	{
        // init the resource
		$ch = curl_init();
		curl_setopt_array($ch, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => 2
		));
        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_ENCODING ,"gzip, deflate");
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
} 
Class Latlon extends Curl{ 
    public function googleAPI($address)
    {
        $key = 'AIzaSyDwtLJ8ditOU1vrNL-v5ofV8IoSD30gNuI';
        $ur = "https://maps.google.com/maps/api/geocode/json?address=".$address."&sensor=false&key=".$key;
        $response=  $this -> processCURL($ur);
        $response = json_decode($response);
        $lat = $response->results[0]->geometry->location->lat;
        $long = $response->results[0]->geometry->location->lng;
        return array('lat'=>$lat,'long'=>$long);
    } 
    public function osmAPI($address)
    {
        $search_url = "https://nominatim.openstreetmap.org/search?q=".$address."&format=json";
        $httpOptions = [
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: Nominatim-Test"
            ]
        ];
        $streamContext = stream_context_create($httpOptions);
        $json = file_get_contents($search_url, false, $streamContext);
        $decoded = json_decode($json, true);
        $lat = $decoded[0]["lat"];
        $long = $decoded[0]["lon"];
        return array('osmlat'=>$lat,'osmlong'=>$long);
    }  
}
if(isset($_POST['addressClean'])&& $_POST['addressClean']!='')
{
    $latlon = new Latlon();
    $google=$latlon->googleAPI($_POST['addressClean']);
    $osm=$latlon->osmAPI($_POST['addressClean']);
    echo json_encode(array_merge($google,$osm));
}else{
    echo json_encode(array("errormessage" => "Enter Correct Address!"));
}
?>