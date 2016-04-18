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
include_once '../handler.xml.php';
include_once '../handler.json.php';
include_once '../config.php';
include_once '../video.app.php';
include_once '../roku.php';

$action = $_GET["action"];

// -------- COMMON HTML
?>

<html class="js flexbox canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths webkit chrome mac js">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script type="text/javascript" async="" src="54078f994f7fb6d23e000006.js"></script>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title data-template="Plex - ?">BZ Video Roku Link</title>
	<link href="http://stubby4j.googlecode.com/git-history/ab03a33515d63419662b461e6dcc73e0f603dd4e/main/resources/ui/images/favicon.ico" rel="shortcut icon">

	<link href="style.css" media="all" rel="stylesheet" type="text/css">
	<script src="application.js" type="text/javascript"></script>

	<link href="style2.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="style_aws.css">

	<style type="text/css">
		.fancybox-margin {
			margin-right: 15px;
		}
	</style>
</head>

<body class="lang-en">
	<div class="site-overlay"></div>

	<div id="container" class="setup">

		<div class="container-fluid header blog">
			<div class="row">
				<div class="col-sm-12 col-md-2 text-left">
				</div>
				<div class="col-md-5 text-left nav-head">

				</div>
				<div class="col-md-5 text-right sub-nav">

				</div>
			</div>
		</div>

		<div class="container-fluid admin">
			<div class="row">

				<script src="parsley.js" type="text/javascript"></script>





HHHHHH






<?php

if(action == "")
{
	
?>	
					<div class="col-lg-12 text-center">
					<form accept-charset="UTF-8" action="http://centos.berzuk.com/app/videos_dev/roku/web/process" class="new_user" data-parsley-validate="" id="new_user" method="post" novalidate="">
						<div style="margin:0;padding:0;display:inline">
							<input name="utf8" type="hidden" value="✓">
							<input name="authenticity_token" type="hidden" value="">
						</div>
						<div class="setup-container">
							<h1>Connect your device</h1>
							<div class="field-container">
								<div class="field-row">
									<div id="error1">
										<div class="arrow-down"></div>
										<ul class="parsley-errors-list" id="parsley-id-9923"></ul>
									</div>
									<div class="icon">
										<i class="icomoon icon-user4"></i>
									</div>
									<div class="fields">
										<input autofocus="autofocus" class="setup-field" data-parsley-errors-container="#error1" data-parsley-required-message="This value is required" id="user_loginuser_login" name="user[login]" placeholder="Username" required="required" type="text" data-parsley-id="9923">
									</div>
								</div>

								<div class="field-row">
									<div id="error2">
										<div class="arrow-down"></div>
										<ul class="parsley-errors-list" id="parsley-id-1747"></ul>
									</div>

									<div class="icon">
										<i class="icomoon icon-key2"></i>
									</div>

									<div class="fields">
										<input autocomplete="off" class="setup-field" data-parsley-errors-container="#error2" data-parsley-required-message="This value is required" id="user_password" name="user[password]" placeholder="Password" required="required" type="password" data-parsley-id="1747">
									</div>
								</div>

								<div class="field-row last">
									<div id="error3">
										<div class="arrow-down"></div>
										<ul class="parsley-errors-list" id="parsley-id-1747"></ul>
									</div>

									<div class="icon">
										<i class="icomoon icon-pin"></i>
									</div>

									<div class="fields">
										<input autocomplete="off" class="setup-field" data-parsley-errors-container="#error3" data-parsley-required-message="PIN Value is required" id="code" name="code" placeholder="PIN" required="required" type="text" maxlength="5" autofocus="autofocus">
									</div>
								</div>


							</div>




							<div class="button">
								<input class="cta-btn" name="commit" type="submit" value="CONNECT">
							</div>


						</div>
						<ul class="parsley-errors-list" id="parsley-id-multiple-userremember_me"></ul>
					</form>

				</div>
	
<?php
	
}



if ($action == "process")
{
	
	$username = $_POST["user"]["login"];
	$password = $_POST["user"]["password"];
	$regCode = $_POST["code"];
	
	
	//print_r($_POST);
	
	$roku = new Roku();
	
	
	
	$ret =  $roku->processLink($username, $password, $regCode);
	
	if($ret==0)
	{
		
		print ('<div class="col-lg-12 text-center">');
		
		print "Invalid Login or Code";
		
		
		print ('</div>');
		
		
		
		
		
	}
	else if($ret == 1)
	{
		
		
		print ('<div class="col-lg-12 text-center">');
		
		print "Success";
		
		
		print ('</div>');
		
		
	}
	else 
	{
		
		
		print ('<div class="col-lg-12 text-center">');
		
		print "Technical issue";
		
		
		print ('</div>');
	}
	
	
	
	
	
	
}



?>

			</div>
		</div>
	</div>

	<div class="container-fluid dark footer">


		<div class="row legal">

			<div class="col-sm-12 col-sm-12 text-right">
				© 2015 Berzuk Videos
			</div>
		</div>


	</div>


	</div>

</body>

</html>

