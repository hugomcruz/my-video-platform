<?php

// ----------------------------------------------------------------------------
// BZ Video Application 
//
// >> File: roku.php 
//
// >> Description: 
// Handle ROKU registration
//
// >> Notes:
//
// Version 		Date			By				Changes
// 1.0			26.Aug.2015		Hugo Cruz		Initial Version
//
//
// ----------------------------------------------------------------------------

//Bootstrap classes
include_once 'handler.xml.php';
include_once 'handler.json.php';
include_once 'config.php';
include_once 'video.app.php';

$action = $_GET["api"];

$deviceID = $_GET["deviceID"];




//XML Handlers
if($action == "code")
{
	
	
	print getCode($deviceID);

}
else if ($action == "link")
{
	$regCode = $_GET["regCode"];

	print getLink($deviceID);

}

else if ($action == "process")
{
	
	$username = $_POST["user"]["login"];
	$password = $_POST["user"]["password"];
	$regCode = $_POST["code"];
	
	
	//print_r($_POST);
	
	$roku = new Roku();
	
	
	
	$ret =  $roku->processLink($username, $password, $regCode);
	
	if($ret==0)
	{
		print "Invalid Login or Code";
		
	}
	else if($ret == 1)
	{
		print "Success";
		
		
	}
	else 
	{
		print "Technical issue";
	}
	
	
	
	
	
	
}



function getCode($deviceID)
{
	$roku = new Roku();
	
	$code = $roku->getCode($deviceID);
	

	$result = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');
	$result->addChild('status','success');
	$result->addChild('regCode',$code);
	$result->addChild('retryInterval','30');
	$result->addChild('retryDuration','900');

	

	return $result->asXML();

}


function getLink($deviceID)
{
	$roku = new Roku();

	$token = $roku->getLink($deviceID);
	

	if($token == '0')
	{
		$result = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');
		$result->addChild('status','failure');
		
	}	
	else if($token == NULL)
	{
		$result = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');
		$result->addChild('status','incomplete');
		
	}
	else 
	{
		$result = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');
		$result->addChild('status','success');
		$result->addChild('regToken',$token);
		$result->addChild('customerID','');
		$result->addChild('creationTime','');
		
		
	}
	

	return $result->asXML();
	
}






function generateRandomString($length = 5) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}






class Roku
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
	}

	
	function getCode($deviceID)
	{
	
		$code = generateRandomString();
		
		
		try
		{
			// DEACTIVATE ANY EXISTING RECORD
			$sql = "UPDATE roku_reg SET active=0 where device_id=?";
			$q = $this->db->prepare($sql);
			$q->execute(array($deviceID));
			
			// INSERT THE RECORD IN THE DB
			$sql = "INSERT into roku_reg(device_id,gen_code,created,active) values(?,?,now(),1)";
			$q = $this->db->prepare($sql);
			$q->execute(array($deviceID,$code));
			
			
		}
		catch(\PDOException $ex)
		{
			 
			return 0;
		}
		
	
		return $code;
	
		
	
	}
	
	
	function getLink($deviceID)
	{
	
	
		
		
		try
		{
				$sql = "select token from roku_reg where device_id=? and active=1";
				$q = $this->db->prepare($sql);
				$q->execute(array($deviceID));
				$row = $q->fetch();	
				
				if($row['token'] == NULL)
				{
					return NULL;
					
				}
				else
					return $row['token'];
				
				
				
		}
		catch(\PDOException $ex)
		{
	
			return 0;
		}
	

	
	
	
	}
	
	
	
	function processLink($username, $password, $code)
	{
		$token = bin2hex(openssl_random_pseudo_bytes(16));

		try
		{
			// Check Username and password
			$sql = "select userid, username,password,email from user where username=? and password=? and active=1 ";
			$q = $this->db->prepare($sql);
			$q->execute(array($username, $password));
			
			$count = $q->rowCount();
				
			
			if($count == 0)
			{
				// Invalid login
				return 0;
			
			}
			
			$qRes = $q->fetch();
			$userid = $qRes['userid'];
			
			// If there is one record then proceed
			
			
			// UPDATE TOKEN
			$sql = "UPDATE roku_reg SET token=?,active=0,updated=now() where gen_code=? and active=1";
			$q = $this->db->prepare($sql);
			$q->execute(array($token, $code));
				
			$count = $q->rowCount();
			
			if($count == 0)
			{
				//Invalid Code
				return 0;
				
			}
			else if($count == 1)
			{
				// Insert into main token DB
				
				$sql = "INSERT token(token,userid,created,validity) values(?,?,now(),now()+100)";
				$q = $this->db->prepare($sql);
				$q->execute(array($token, $userid));
				
				
				
				return 1;
				
			}
					
		}
		catch(\PDOException $ex)
		{
	
			return -1;
		}
	
	
	
	
	
	}
	
	
	

}

?>

