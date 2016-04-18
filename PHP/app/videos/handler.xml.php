<?php

// ----------------------------------------------------------------------------
// BZ Video Application 
//
//>> File: handler.xml.php 
//
// >> Description: 
// Handle XML API Requests
//
// >> Notes:
//
// Version 		Date			By				Changes
// 1.0			25.Aug.2015		Hugo Cruz		Initial Version
//
//
// ----------------------------------------------------------------------------

class XmlHandler
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
		
		$app = new VideoApp();
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
		
		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><login/>');
    	$track = $xml->addChild('status',$status);
    	$track = $xml->addChild('message',$message);
    	$track = $xml->addChild('token',$token);
    	
		print $xml->asXML();

	}

	function usage($token, $api, $feed_id)
	{
		
		//Execute here backend function
		$app = new VideoApp();
		$app->usage($token, $api, $feed_id);
		
		//always reply with 200 HTTP code as this does not require response
		header("HTTP/1.1 200 OK");
		
		// Only with PHP 5.4
		//http_response_code(200);		
	
	}



	// The XML handling categories is not a 1 to 1 to the backend data
	// Needs to query the sub-categories and provide all the data
	function categories($getData)
	{
		
		$app = new VideoApp();
		
		
		
		
		$result = $app->categories();
		
		if($result == FALSE)
		{
			$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');
			$xml->addChild('status','FAIL');
			$xml->addChild('message','Invalid token or system error. Re-authenticate.');
			
		}
		else 
		{
		
		
			$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><categories/>');
			$banner = $xml->addChild('banner_ad');
			$banner->addAttribute('sd_img','image1.gif');
			$banner->addAttribute('hd_img','image1.gif');
		
		
		
			foreach($result as &$value)
			{
				$category = $xml->addChild('category');
				$category->addAttribute('title',$value['name']);
				$category->addAttribute('description','');
				$category->addAttribute('sd_img',$value['thumb_img']);
				$category->addAttribute('hd_img',$value['thumb_img']);
			
			
				//Query here the subCategories
				$scat = $app->subCategories($value['id']);

				foreach($scat as &$scat_item)
				{	
					$leaf = $category->addChild('categoryLeaf');
					$leaf->addAttribute('title',$scat_item['name']);
					$leaf->addAttribute('description','');
					$leaf->addAttribute('feed',$scat_item['id']);
				}
			
						
			
		
			}
		
		}
		
		print $xml->asXML();
	
	}

	function feed($getData)
	{
		
		//$this->ipRelated();
		
		
		$app = new VideoApp();
		$result = $app->feed($getData['scat']);
		
		
		if($result == FALSE)
		{
			$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');
			$xml->addChild('status','FAIL');
			$xml->addChild('message','Invalid token or system error. Re-authenticate.');
			
		}
		else 
		{
		
			$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><feed/>');
			$track = $xml->addChild('resultLength',count($result));
			$track = $xml->addChild('endIndex',count($result));
		
	
		
			foreach($result as &$value)
			{
		
				$item = $xml->addChild('item');
				$item->addAttribute('sdImg', $value['thumb_img']);
				$item->addAttribute('hdImg', $value['thumb_img']);
				$item->addchild('title',$value['title']);
				$item->addchild('contentId',$value['id']);
				$item->addchild('contentType',$value['stream_format']);
				$item->addchild('contentQuality',$value['stream_quality']);
				$media = $item->addchild('media');
				$media->addchild('streamFormat',$value['stream_format']);
				$media->addchild('streamQuality',$value['stream_quality']);
				$media->addchild('streamBitrate',$value['stream_bitrate']);
				$media->addchild('streamUrl',$value['url']);
				$item->addchild('synopsis',$value['synopsys']);
				$item->addchild('genres','Cartoon');
				$item->addchild('runtime',$value['runtime']);
		
			}
		}
		
		print $xml->asXML();
		
	
	}

	//AUX functions
	function loginErrorXml($code, $message)
	{
		$xml = new SimpleXMLElement('<login/>');

    	$track = $xml->addChild('token','');
    	$track = $xml->addChild('satus',$code);
    	$track = $xml->addChild('message',$message);
    	
		return $xml->asXML();
	
	}


}







?>