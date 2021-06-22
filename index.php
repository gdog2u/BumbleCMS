<?php
	include("config.php");
	include("class_load.php");
	// Have to initialize before including functions	
	$dbh = new PDO(DB_DSN, DB_USER, DB_PASS);
	
	include("functions.php");

	try{
		$bumble = loadSiteSettings();
	}catch(Exception $e){
		exit();
	}

	$slug = getSlugFromURI();

	$template = getSlugTemplate($slug[0]??"");

	if(file_exists(THEME_PATH.$template))
	{
		include(THEME_PATH.$template);
	}
	else
	{
		display404();
	}
