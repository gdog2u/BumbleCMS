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

	if(count($slug) == 0)
	{
		include($bumble['theme-path']."home.php");
		exit();
	}

	$template = getSlugTemplate($slug[0]??"");

	if(!empty($template) && file_exists($bumble['theme-path'].$template))
	{
		include($bumble['theme-path'].$template);
	}
	else
	{
		display404();
	}
