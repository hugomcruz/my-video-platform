<?php

// ----------------------------------------------------------------------------
// >> File: handler.json.php 
//
// >> Description: 
// Handle XML API Requests
//
// >> Notes:
//
// Version 		Date			By				Changes
// 1.0			23.May.2015		Hugo Cruz		Initial Version
//
//
// ----------------------------------------------------------------------------

class JsonHandler
{


	// Initialize constructor here
	//- Get IP Address
	//- Get Header Token
	// 




	public function login($getData)
	{


		//print_r($_SERVER);
		//print_r(apache_request_headers());

		//Validate Get and Check for parameters
		$username = $getData["user"];
		$password = $getData["pass"];
	
		if($username == "" or $username == NULL or $password == "" or $password == NULL)
		{
			// Send the headers
			header('Content-type: text/xml');
			header('Pragma: public');
			header('Cache-control: private');
			header('Expires: -1');
		
			$xmlString = $this->loginErrorXml("404", "Username or Password missing or empty.");
			print $xmlString;
			
			return;
		}
		
		// Execute the code here to get token from DB layer
		
		
		// TESTING NOW - 20May Hugo
		
		$app = new CartoonApp();
		$result = $app->login($username,$password);
		
		$token = $result['token'];
		$status = $result['status'];
		$message = $result['message'];
		
		// -----
		
		//Return the token here
		// Send the headers
		header('Content-type: text/xml');
		header('Pragma: public');
		header('Cache-control: private');
		header('Expires: -1');
		
		$xml = new SimpleXMLElement('<login/>');
    	$track = $xml->addChild('status',$status);
    	$track = $xml->addChild('message',$message);
    	$track = $xml->addChild('token',$token);
    	
		print $xml->asXML();

	}

	function usage($token, $api, $feed_id)
	{
		
		//Execute here backend function
		$app = new CartoonApp();
		$app->usage($token, $api, $feed_id);
		
		//always reply with 200 HTTP code as this does not require response
		header("HTTP/1.1 200 OK");
		
		// Only with PHP 5.4
		//http_response_code(200);		
	
	}

	function languages($getData)
	{
		$app = new CartoonApp();
		$languageList = $app->languages();
			
		
		$json= array();
		$json['language'] = array();
		
		
		

		foreach($languageList as &$value)
		{
	
			$languageNode = array();
			$languageNode['code'] = $value['code'];
			$languageNode['name'] = $value['name'];
			
			array_push($json['language'],$languageNode);
		
		}
		
		
		$jsonString = json_encode($json);
		
		print $jsonString;	
	}

	// The XML handling categories is not a 1 to 1 to the backend data
	// Needs to query the sub-categories and provide all the data
	function categories($getData)
	{
		$app = new CartoonApp();
		$result = $app->categories($getData['language']);
		
		
		$json= array();
		

		
		$json['category'] = array();
		
	
		
		
		foreach($result as &$value)
		{
			
			$tmpCat = array();
		
			$tmpCat['title'] = $value['name'];
			$tmpCat['description'] = "No desc FIX";
			$tmpCat['thumb_img'] = $value['thumb_img'];
		
			$tmpCat['subcategory']	= array();
				
			//Query here the subCategories
			$scat = $app->subCategories($value['id']);

			foreach($scat as &$scat_item)
			{	
				
				$tmpScat = array();
				$tmpScat['title'] = $scat_item['name'];
				$tmpScat['scat_id'] = $scat_item['id'];
				
				array_push($tmpCat['subcategory'], $tmpScat);
				

			}
			
				array_push($json['category'], $tmpCat);		
			
		
		}
		
		print json_encode($json);
	
	
	}

	function feed($getData)
	{
		
		$json= array();
		
		$json['feeds'] = array();
		
		
		$app = new CartoonApp();
		$result = $app->feed($getData['scat']);
				

		
		foreach($result as &$value)
		{
			
			$tmpFeed = array();
			
			$tmpFeed['feed_id'] = $value['id'];
			$tmpFeed['title'] = $value['title'];
			$tmpFeed['synopsys'] = $value['synopsys'];
			$tmpFeed['thumb_img'] = $value['thumb_img'];
			$tmpFeed['stream_format'] = $value['stream_format'];
			$tmpFeed['stream_quality'] = $value['stream_quality'];
			$tmpFeed['stream_bitrate'] = $value['stream_bitrate'];
			$tmpFeed['runtime'] = $value['runtime'];
			$tmpFeed['stream_url'] = $value['url'];
			$tmpFeed['thumb_img'] = $value['thumb_img'];

			
			array_push($json['feeds'], $tmpFeed);
		
		}
		
		print json_encode($json);
		
	
	}

	


}




?>