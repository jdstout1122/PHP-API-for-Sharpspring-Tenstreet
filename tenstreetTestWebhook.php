<?php header("Content-type: text/xml; charset=utf-8");
 function sendResponse() {
    $response = '<?xml version="1.0" encoding="utf-8"?>';
    $response .= '<TenstreetResponse>
    				<Status>ACCEPTED</Status>
    				<Description>Api testing.</Description>
				</TenstreetResponse>';
            return $response;
 }
 header("Content-type: text/xml; charset=utf-8");echo sendResponse();



$file = fopen("tenstreet.txt",'w');
$dataPOST = trim(file_get_contents('php://input'));
$data = simplexml_load_string($dataPOST);

$encoded_data = json_encode($data);
// Convert into associative array
$newArrData = json_decode($encoded_data, true);
 


// file_put_contents($file, );
fwrite($file, print_r($newArrData, true));


$param = array(
	'firstName' =>'',
	'lastName' =>'',
	'emailAddress' =>'',
	'phoneNumber' =>'', 
  'city' =>'',
  'state' =>'',
  'zipcode' =>''
);

foreach ($newArrData['PersonalData'] as $contact) {
	
	// foreach ($contact as $temp) {
	// 	fwrite($file, print_r($temp, true));
	// }
	

	// $PersonName = $contact->PersonName;
	if(isset($contact['GivenName']))
	{
		$param['firstName'] = $contact['GivenName']; 
	}
	if(isset($contact['FamilyName']))
	{
		$param['lastName'] = $contact['FamilyName']; 
	}
	
}

// foreach ($newArrData['PersonalData']['ContactData'] as $email) {
// 	var_dump($email['InternetEmailAddress']);
// 	die;
// 	if(isset($email['InternetEmailAddress']))
// 	{
// 		$param['emailAddress'] = $email['InternetEmailAddress'];		
// 	}
// }
$param['emailAddress'] = $newArrData['PersonalData']['ContactData']['InternetEmailAddress'];
$param['phoneNumber'] = $newArrData['PersonalData']['ContactData']['PrimaryPhone'];

$param['city'] = $newArrData['PersonalData']['ContactData']['Municipality'];
$param['state'] = $newArrData['PersonalData']['ContactData']['Region'];
$param['zipcode'] = $newArrData['PersonalData']['ContactData']['PostalCode'];



postData($param);
fclose($file);
  function postData($params=[])
  {
     /** Get all leads with a limit of 500 results */
  $method = 'createLeads';                                                                 
  // $params = array('emailAddress' => "justintest@gmail.com");          
   $requestID = session_id();       
   $accountID = 'C5B5F0AADF291955425AE8C6F8869D20';
   $secretKey = 'C5103ACB5C7449C4AC3E27D8CB47C571';                                                     
   $data = array(                                                                                


       'method' => $method,                                                                      


       'params' => $params,                                                                      


       'id' => $requestID,                                                                       


   );                                                                                            


                                                                                                 


   $queryString = http_build_query(array('accountID' => $accountID, 'secretKey' => $secretKey)); 


   $url = "http://api.sharpspring.com/pubapi/v1/?$queryString";                             


                                                                                                 


   $data = json_encode($data);                                                                   


   $ch = curl_init($url);                                                                        


   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                              


   curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                                  


   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                               


   curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                   


       'Content-Type: application/json',                                                         


       'Content-Length: ' . strlen($data)                                                        


   ));                                                                                           


                                                                                                 


   $result = curl_exec($ch);                                                                     


   curl_close($ch);                                                                              

  }


?>