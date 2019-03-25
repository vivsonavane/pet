<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

function makeAPICall($method, $url, $petData){
   $curl = curl_init();

   //Add a new pet to the store
   if( $method == 'POST' ){
   		curl_setopt($curl, CURLOPT_POST, 1);
        if ($petData)
        	curl_setopt($curl, CURLOPT_POSTFIELDS, $petData);
   }
   //Update an existing pet
   else if( $method == 'PUT' ){
   		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        if ($petData)
        	curl_setopt($curl, CURLOPT_POSTFIELDS, $petData);
   }
   // delete a pet
   else if( $method == 'DEL' ){
   		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        if ($petData)
        	$url = sprintf("%s?%s", $url, http_build_query($petData));
   }
   // get pet ID, find pets by status
   else{
   		if ($petData)
   			$url = sprintf("%s?%s", $url, http_build_query($petData));
   }
   // default options
   curl_setopt($curl, CURLOPT_URL, $url);
   // set your API Key and conent type
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'APIKEY: special-key',
      'Content-Type: application/json',
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

   // Executing.
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
}

//dummy data. For addition and updation I have used the same array.
$petData_array =  array (
  'id' => 0,
  'category' =>
  array (
    'id' => 0,
    'name' => 'string',
  ),
  'name' => 'Koala King',
  'photoUrls' =>
  array (
    0 => 'string',
  ),
  'tags' =>
  array (
    0 =>
    array (
      'id' => 0,
      'name' => 'string',
    ),
  ),
  'status' => 'available',
);

$petData_array_2 =  array (
  'id' => 0,
  'category' =>
  array (
    'id' => 0,
    'name' => 'string',
  ),
  'name' => 'Koala King updated',
  'photoUrls' =>
  array (
    0 => 'string',
  ),
  'tags' =>
  array (
    0 =>
    array (
      'id' => 0,
      'name' => 'string',
    ),
  ),
  'status' => 'available',
);

//Add a new pet to the store
$make_call_POST = makeAPICall('POST', 'http://petstore.swagger.io/v2/pet', json_encode($petData_array));
$response1 = json_decode($make_call_POST, true);
if ( !empty( $response1 ) )
{
	echo "New pet added with ". $response1['name']."<br /><br />";
	$myPetID = $response1['id'];
	//echo "<pre>"; print_r($response1); echo "</pre>";
}


//Update an existing pet
$make_call_PUT = makeAPICall('PUT', 'http://petstore.swagger.io/v2/pet', json_encode($petData_array_2));
$response2 = json_decode($make_call_PUT, true);
if ( !empty( $response2 ) )
{
	echo "Updated pet with new ". $response2['name']."<br /><br />";
	$myUpdatedPetID = $response2['id'];
	//echo "<pre>"; print_r($response2); echo "</pre>";
}


// get pet ID
$petID = $myPetID;
$make_call_GET = makeAPICall('GET', 'http://petstore.swagger.io/v2/pet/'.$petID, false);
$response3 = json_decode($make_call_GET, true);
if ( !empty( $response3 ) )
{
	echo "Pet is returned through pet ID ". $petID. " and the name of pet is " . $response3['name']."<br /><br />";
	//echo "<pre>"; print_r($response3); echo "</pre>";
}


// find pets by status
//$status='available,pending,sold';
$status='available';
$make_call_GET_status = makeAPICall('GET', 'http://petstore.swagger.io/v2/pet/findByStatus?status='.$status, false);
$response4 = json_decode($make_call_GET_status, true);
if ( !empty( $response4 ) )
{
	echo "All pet with available status is returned. Our new pet is also returns at the end of an array.<br />";
	echo "<pre>"; print_r($response3); echo "</pre>";
}


// delete a pet
$petID = $myPetID;
$make_call_DELETE = makeAPICall('DEL', 'http://petstore.swagger.io/v2/pet/'.$petID, false);
$response5 = json_decode($make_call_DELETE, true);
echo "<pre>"; print_r($response5); echo "</pre>";

?>