<?php

// ----------------------------------------------------------------------------
// >> File: video.app.php 
//
// >> Description: 
// Handle XML API Requests
//
// >> Notes:
//
// Version 		Date			By				Changes
// 1.0			24.Aug.2015		Hugo Cruz		Initial Version
//
//
// ----------------------------------------------------------------------------





// TODO to IMPROVE
// Generate URL queries database all the time - Cache data between requests or in memory
//
//
// Extracting the token must be outside app


include ("config.php");



class VideoApp
{

	var $db;
	
	// Global variables on each instantiation
	var $userID;
	var $token;
	var $ipAddress;
	
	

	function __construct() 
	{
       global $dbConfig;

		$dbURL = $dbConfig['type'].":"."host=".$dbConfig['host'].";"."dbname=".$dbConfig['database'];

		// PDO
		$this->db = new PDO($dbURL, $dbConfig['user'], $dbConfig['pass']);
		//$db->setAttribute( \PDO::ATTR_EMULATE_PREPARES, false );
		$this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 
		$this->db->exec("set names utf8");
	
		$headers = getallheaders();
		
		$this->token = $headers['token'];
	
		//
		if(!is_null($this->token))
		{
			$this->userID = $this->getUserFromToken($this->token);
				
		}
	
		$this->ipAddress = $this->getIPAddress();
		
   	}


	
	function login($username, $pass)
	{
		
		try
		{
			$stmt = $this->db->query("SELECT username,password,active FROM user where username='$username'");
			$recs = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(\PDOException $ex) 
		{
    	
    		return $this->loginResult("ERROR", $ex->getMessage(),'');
		}
		
		if(count($recs) == 0)
		{
			return $this->loginResult("FAIL", "User does not exist.", '');
		}
		else if($recs[0]['active'] == 0)
		{
		
			return $this->loginResult("FAIL", "User is disabled.", '');
			
		}
		else
		{
		
			if($recs[0]['password'] == $pass )
			{
				// PASS - generate token and return
				
				$token = uniqid();
				
				//Store the token in login DB - TODO
				
				return $this->loginResult("PASS", "Authentication success.", $token);
						
				
			}
			else
			{
				return $this->loginResult("FAIL", "Wrong password.", '');
			
			}
		
		}
		
	
	}
	
	

	
	
	function categories()
	{
	
		
		$status = $this->validateToken($this->token);
		
		if ($status == FALSE)
			return FALSE;
		
			
		
		
		
		$date = date('Y-m-d H:i:s');
	
		//$this->kpiNavigation($this->userID, "cat", $date, '', '');
	
		try
		{
			$stmt = $this->db->query("	select id, name, thumb_img 
										from category 
										where active=1
										order by sequence");
			$recs = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
		}
		catch(\PDOException $ex) 
		{
    		//Return ERROR		
   		}
	
		if(count($recs) == 0)
		{
			//Return some error here
		}
		
		
		$categoryList = array();
	
		foreach($recs as &$value)
		{
		
			$cat = array();
			$cat['id'] = $value['id'];
			$cat['name'] = $value['name'];
			$cat['thumb_img'] = $value['thumb_img'];
			
			array_push($categoryList, $cat);	
		
		
		}
	
		return $categoryList;
		
		
	}
	
	function subCategories($catID)
	{
		
		
		try
		{
			$stmt = $this->db->query("	select id,cat_id,name 
										from sub_category
										where cat_id=$catID and active=1
										order by sequence");
			$recs = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
		}
		catch(\PDOException $ex) 
		{
    		//Return ERROR		
   		}
	
		if(count($recs) == 0)
		{
			//Return some error here
			return null;
		}
		
		
		$subCategoryList = array();
	
		foreach($recs as &$value)
		{
		
			$scat = array();
			$scat['id'] = $value['id'];
			$scat['cat_id'] = $value['cat_id'];
			$scat['name'] = $value['name'];
			
			array_push($subCategoryList, $scat);	
		
		
		}
	
		return $subCategoryList;
	
	
	}
	
	function feed($scat)
	{
		//Validate Token
		$status = $this->validateToken($this->token);
		
		if ($status == FALSE)
			return FALSE;
		
		
		
		//Get User DC preference
		$countryCode = $this->getCountryCode($this->ipAddress);
		$dc_id = $this->getDataCenter($countryCode);
	
		$date = date('Y-m-d H:i:s');
		$this->kpiNavigation($this->userID, "scat", $date, '', $scat);
	
	
	
		try
		{
			$stmt = $this->db->query("	select id,subcat_id, title, stream_format, stream_quality, thumb_img, stream_bitrate, synopsys, runtime, file_path, file_name
										from feed
										where subcat_id = $scat and active=1
										order by sequence");
			$recs = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			
		}
		catch(\PDOException $ex) 
		{
    		print $ex;	
   		}
	
		if(count($recs) == 0)
		{
			//Return some error here
			return null;
		}
		
		
		$feedList = array();
		
		
	
		foreach($recs as &$value)
		{
		
			$feed = array();
			$feed['id'] = $value['id'];
			$feed['subcat_id'] = $value['subcat_id'];
			$feed['title'] = $value['title'];
			$feed['stream_format'] = $value['stream_format'];
			$feed['stream_quality'] = $value['stream_quality'];
			$feed['stream_bitrate'] = $value['stream_bitrate'];
			$feed['synopsys'] = $value['synopsys'];
			$feed['runtime'] = $value['runtime'];
			$feed['file_path'] = $value['file_path'];
			$feed['file_name'] = $value['file_name'];
			$feed['thumb_img'] = $value['thumb_img'];
	
			$url = $this->generateURL($dc_id, $value['file_path'], $value['file_name']);
			$feed['url'] = $url;
			
			
			
			array_push($feedList, $feed);	
		
		
		}
	
		return $feedList;
	
	
	}
	
	
	
	public function usage($token,$api, $feedID)
	{
		
		$countryCode = $this->getCountryCode($this->ipAddress);
		//$countryCode = "";
		$date = date('Y-m-d H:i:s');
		
		$this->kpiVisualization($feedID, $this->userID, $date, 'device', $api, $this->ipAddress, $countryCode );
	
	}
	
	
	
	
	
	//// Private functions
	
	private function loginResult($status, $message, $token)
	{
		$result = array();
		$result['status'] = $status;
		$result['message'] = $message;
		$result['token'] = $token;
	
		return $result;
	}
	
	
	private function validateTokenNEW($token)
	{
		
		
		try
		{
			// Get User ID if everything is valid (token and validity date) - Igonre validity on first stage
			//$sql = "select userid from token where token=? and validity > now()";
			$sql = "select userid from token where token=?";
			$q = $this->db->prepare($sql);
			$q->execute(array($deviceID));
		
			$rowCount = $q->rowCount();
			if($rowCount == 0)
			{
				return 0;
			}
			else 
				return $q->fetch()['userid'];
			
		}
		catch(\PDOException $ex)
		{
			return -1;
		}
		
		
	}
	
	
	
	private function getCustomerProfile($token)
	{
		try
		{
			$stmt = $this->db->query("SELECT base_url FROM datacenter where id='$dc_id'");
			$recs = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
		}
		catch(\PDOException $ex) 
		{
    	   	return $this->loginResult("ERROR", $ex->getMessage(),'');
		}
	
	}
	
	
	private function getCountryCode($ipAddress)
	{
	
		$countryCode = apc_fetch($ipAddress, $success); 
		
		if($success == FALSE)
		{ 
		
			$details = json_decode(file_get_contents("http://ipinfo.io/{$ipAddress}/json"));
		
			$creatorLocation = $details->country;
			
			if($creatorLocation == '' or $creatorLocation == NULL)
				$creatorLocaiton = 'XX';
		
			//$iptolocation = 'http://api.hostip.info/country.php?ip=' . $ipAddress
			//$creatorLocation = file_get_contents($iptolocation);
		
		
			$result = apc_store($ipAddress, $creatorLocation, 3600);
	
			$countryCode = $creatorLocation;
		
		}
	
		return $countryCode;
	
	
	}
	
	
	
	// Validate the provided token
	private function validateToken($token)
	{
	
		try
		{
			$stmt = $this->db->query("SELECT userid, created, validity FROM token where token='$token'");
			$recs = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
		}
		catch(\PDOException $ex) 
		{
 			return FALSE;
 			// Log and alert here
 			
 			
 		}
		
		if(count($recs) == 0)
		{
			//Token does not exists
			return FALSE;
			
		}
		
		/*$tokenExpireTime = strtotime($recs[0]['validity']);
		
		
		if( $tokenExpireTime > time())
		{
			return TRUE;
				
		}
		else
		{
			return FALSE;
			
		}
		
		*/
		
		return TRUE;
		

	}


	private function generateURL($dc_id, $path, $filename)
	{
		
		$deviceType = "mediabox";
		
		
		try
		{
			$stmt = $this->db->query("SELECT base_url FROM datacenter where id='$dc_id'");
			$recs = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
		}
		catch(\PDOException $ex) 
		{
    		// Error here	   	
		}
		
		if(count($recs) == 0)
		{
			//Return some error here
			return null;
		}
				
		$base_url = $recs[0]['base_url'];
		return rtrim($base_url,'/')."/" .$deviceType . "/".trim($path,'/')."/" .$filename;
	
	
	}


	private function kpiNavigation($userID, $actionName, $actionTime, $catLanguage, $feedScat )
	{
	
		try
		{
		
			$sql = "INSERT INTO kpi_navigation(user_id, action_name, action_time, cat_language, feed_scat) 
			VALUES (:user_id,:action_name,:action_time,:cat_language,:feed_scat)";
			
			
			$stmt = $this->db->prepare($sql);
			
			//$stmt->bindParam(':user_if', $id);
			
			$stmt->execute(
				array(	':user_id'=>$userID,
                  		':action_name'=>$actionName,
                  		':action_time'=>$actionTime,
                  		':cat_language'=>$catLanguage,
                  		':feed_scat'=>$feedScat
                  	 )
                  	);
			
			
			
			
		}
		catch(\PDOException $ex) 
		{
			//Log error
			//print $ex->getMessage();
    	   	//return $this->loginResult("ERROR", $ex->getMessage(),'');
		}
	
	

	
	}




	private function kpiVisualization($feedID, $userID, $viewDate, $device, $api, $ipAddress, $countryCode )
	{
	
		try
		{
		
			$sql = "INSERT INTO kpi_visualizations(feed_id, user_id, view_date, device, api, ip_address, country_code) 
			VALUES (:feed_id, :user_id, :view_date, :device, :api, :ip_address, :country_code)";
			
			
			$stmt = $this->db->prepare($sql);
			
			//$stmt->bindParam(':user_if', $id);
			
			$stmt->execute(
				array(	':feed_id'=>$feedID,
                  		':user_id'=>$userID,
                  		':view_date'=>$viewDate,
                  		':device'=>$device,
                  		':api'=>$api,
                  		':ip_address'=>$ipAddress,
                  		':country_code'=>$countryCode,
                  		
                  	 )
                  	);
			
			
			
			
		}
		catch(\PDOException $ex) 
		{
			//Log error
			print $ex->getMessage();
    	   	//return $this->loginResult("ERROR", $ex->getMessage(),'');
		}
	
	

	
	}



	private function getUserFromToken($token)
	{

		try
		{
			$stmt = $this->db->query("SELECT userid FROM token where token='$token'");
			$recs = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			
		}
		catch(\PDOException $ex) 
		{
 			return null;
 		}
		
		if(count($recs) == 0)
		{
			//Return some error here
			return null;
		}
				
		return $recs[0]['userid'];
		
	
	}
	
	

	
	
	

	private function getIPAddress()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
		{
    		$ip = $_SERVER['HTTP_CLIENT_IP'];
		} 
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
		{
    		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} 
		else 
		{
    		$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		return $ip;
		
	}


	// Datacenter ID - Default DC will always be CDN
	private function getDataCenter($countryCode)
	{
				
		$country_dc = apc_fetch('country_dc', $success); 
		
		if($success == FALSE)
		{ 
		
			//Hydrate cache from database
			try
			{
				$stmt = $this->db->query("SELECT country_code,dc_id FROM country_dc");
				$recs = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			}
			catch(\PDOException $ex) 
			{
 				return null;
 			}
			
			$country_dc = array();
		
			foreach($recs as &$value)
			{
				$country_dc[$value['country_code']] = $value['dc_id'];
			
			}
			
			$result = apc_store("country_dc", $country_dc, 3600);   
		} 
		
		// Calculate DC ID
		$dc = $country_dc[$countryCode];
		
		if($dc == NULL)
			return "X1";
		else
			return $dc;
		
		
		
	
	
	}

}

?>
