<?php 
    if(!empty($_POST))
	{
		   $myfile = fopen("test_response_data.txt", "a") or die("Unable to open file!");
			
		    $POSTCONF = array('ClientId'=>'663','Password'=>'3E5TQwNAmbesBc6fHyr','Service'=>'subject_upload','Mode'=>'PROD','Source'=>"SharpSpring-USTransport",'CompanyId'=>'2435','CountryCode'=>'US');
			
			$post['firstName'] = $_POST['firstName'];
			$post['lastName'] = $_POST['lastName'];
			$post['emailAddress'] = $_POST['emailAddress'];
			$post['displayName'] = $_POST['displayName'];
			$post['phoneNumber'] = $_POST['phoneNumber'];
			$post['website'] = $_POST['website'];
			$post['title'] = $_POST['title'];
			
			$post['description'] = $_POST['description'];
			$post['industry'] = $_POST['industry'];
			$post['city'] = $_POST['city'];
			$post['state'] = $_POST['state'];
			$post['country'] = $_POST['country'];
			$post['zipcode'] = $_POST['zipcode'];
			
			$post['companyName'] = $_POST['companyName'];
			$post['faxNumber'] = $_POST['faxNumber'];
			
			$post['officePhoneNumber'] = $_POST['officePhoneNumber'];
			$post['mobilePhoneNumber'] = $_POST['mobilePhoneNumber'];
			$post['phoneNumberLink'] = $_POST['phoneNumberLink'];
			$post['phoneNumberInternational'] = $_POST['phoneNumberInternational'];
			$post['phoneNumberExtension'] = $_POST['phoneNumberExtension'];
			
			$curl_post['Authentication']['ClientId'] = $POSTCONF['ClientId'];
			$curl_post['Authentication']['Password'] = $POSTCONF['Password'];
			$curl_post['Authentication']['Service']  = $POSTCONF['Service'];
			
			$curl_post['Mode'] = $POSTCONF['Mode'];
			$curl_post['Source'] = $POSTCONF['Source'];
			$curl_post['CompanyId'] = $POSTCONF['CompanyId'];
			$curl_post['PersonalData']['PersonName']['Prefix'] = ($post['Prefix'])?$post['Prefix']:'NA';
			$curl_post['PersonalData']['PersonName']['GivenName'] = $post['firstName'];
			$curl_post['PersonalData']['PersonName']['FamilyName'] = $post['lastName'];
			//$curl_post['PersonalData']['PostalAddress']['CountryCode'] = ($POSTCONF['CountryCode'])?$POSTCONF['CountryCode']:'US';
			$curl_post['PersonalData']['PostalAddress']['CountryCode']  = $POSTCONF['CountryCode'];
			$curl_post['PersonalData']['PostalAddress']['Municipality'] = $post['city'];
			$curl_post['PersonalData']['PostalAddress']['Region'] = $post['state'];
			$curl_post['PersonalData']['PostalAddress']['PostalCode'] = $post['zipcode'];
			$curl_post['PersonalData']['PostalAddress']['Address1'] = ($post['address1'])?$post['address1']:'NA';
			$curl_post['PersonalData']['ContactData']['InternetEmailAddress'] = $post['emailAddress'];
			$curl_post['PersonalData']['ContactData']['PrimaryPhone']  = $post['phoneNumber'];
			
			$xml = array2xml($curl_post);
			$dom = new DOMDocument;
			$dom->preserveWhiteSpace = FALSE;
			$dom->loadXML($xml);  
			$error_msg='';
			$post_address = 'https://api.tenstreet.com/';
			$ch = curl_init();curl_setopt($ch, CURLOPT_URL, $post_address);
			curl_setopt($ch, CURLOPT_VERBOSE,1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=utf-8'));
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $xml); // add POST fields
			$response_xml = curl_exec($ch);// run the whole process
			if (curl_errno($ch)) {
				$error_msg = curl_error($ch);
			}
			curl_close($ch); // Always close the connection

			//echo $response_xml; // $response_xml now contains Tenstreet response
			
			//$txt = json_encode($post);
			$time = '============='.date('Y-m-d H:i:s');
			//fwrite($myfile, "\n". $txt);
			fwrite($myfile, "\n". $time);
			fwrite($myfile, "\n". "================Response");
			fwrite($myfile, "\n". $response_xml);
			if(!empty($error_msg)){
			fwrite($myfile, "\n". "================ERROR");
			fwrite($myfile, "\n". $error_msg);
			}
			
			fclose($myfile);
			
	}
	else{
		echo "0";
	}
	
	

	function array2xml($array, $xml = false){

    if($xml === false){
        $xml = new SimpleXMLElement('<TenstreetData/>');
    }

    foreach($array as $key => $value){
        if(is_array($value)){
            array2xml($value, $xml->addChild($key));
        } else {
            $xml->addChild($key, $value);
        }
    }

    return $xml->asXML();
}



?>