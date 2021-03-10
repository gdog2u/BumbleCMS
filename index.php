<?php
	require_once("config.php");
	require_once(CLASS_PATH."/post.php");

	if(isset($_GET['p']) && is_numeric($_GET['p'])):
		$post = Post::getByID($_GET['p']);
?>
	<!-- <pre><?=print_r($post, true) ?></pre> -->
	<h1><?=$post->Title ?></h1>
	<div class="summary"><?=$post->Summary ?></div>
	<div class="body"><?=$post->Body ?></div>
<?php
	endif;
?>
