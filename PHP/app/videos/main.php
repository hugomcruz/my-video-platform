<?php

// ----------------------------------------------------------------------------
// BZ Video Application 
//
// >> File: main.php 
//
// >> Description: 
// Main file of the cartoons application.
// Routes API calls to other handlers
//
// >> Notes:
//
// Version 		Date			By				Changes
// 1.0			25.Aug.2015		Hugo Cruz		Initial Version
//
//
// ----------------------------------------------------------------------------

//Bootstrap classes
include_once 'handler.xml.php';
include_once 'handler.json.php';
include_once 'config.php';
include_once 'video.app.php';



// Retrieve the relevant headers

$type = $_GET["type"];
$api = $_GET["api"];

//XML Handlers
if($type == "xml")
{

	//Initialize XML handler
	$handler = new XmlHandler();
	

	if($api == "login")
	{
		$handler->login($_GET);
	}
	else	
	if($api == "categories")
	{
		$handler->categories($_GET);
	}
	else
	if($api == "feed")
	{
		$handler->feed($_GET);
	}
	else
	if($api == "usage")
	{
		$handler->usage("token","xml", $_GET['feed_id']);
	}
	else
		//Trying to use inexistent API
		
		header("HTTP/1.1 404 Not Found");
		//http_response_code(404);


}

// JSON Handlers 
else if($type == "json")
{

	//Initialize XML handler
	$handler = new JsonHandler();

	if($api == "login")
	{
		
	}
	else
	if($api == "categories")
	{
		$handler->categories($_GET);
	}
	else
	if($api == "feed")
	{
		$handler->feed($_GET);
	}
	else
	if($api == "usage")
	{
		$handler->usage("token","json", $_GET['feed_id']);
	}
	else
		//Trying to use inexistent API
		http_response_code(404);

}
else 
	http_response_code(404);




