<?php
	include("config.php");
	include("class_load.php");
	// Have to initialize before including functions	
	$dbh = new PDO(DB_DSN, DB_USER, DB_PASS);
	
	include("functions.php");

	$slug = getSlugFromURI();

	$template = getSlugTemplate($slug[0]);

	if(file_exists(VIEWS_PATH.$template))
	{
		include(VIEWS_PATH.$template);
	}
	else
	{
		display404();
	}
?>
