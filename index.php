<?php
	include("config.php");
	include("class_load.php");
	// Have to initialize before including functions	
	$dbh = new PDO(DB_DSN, DB_USER, DB_PASS);
	
	include("functions.php");

	getSlugTemplate("");

	if(isset($_GET['p']) && is_numeric($_GET['p'])):
		$post = Post::getByID($_GET['p']);
?>
	<pre><?=print_r($post, true) ?></pre>
	<h1><?=$post->Title ?></h1>
	<div class="summary"><?=$post->Summary ?></div>
	<div class="body"><?=$post->Body ?></div>
<?php
	endif;
?>
