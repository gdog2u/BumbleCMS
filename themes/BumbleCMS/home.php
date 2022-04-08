<?php
    /**
     * Home.php
     * @author Bee Hudson
     */
    include_once('functions.php');
    
    include_once('partials/header.php');
?>

<h1>Welcome to my site!</h1>

<div id="recent-posts">
	<h1>Recent Posts:</h1>
<?php
	$posts = Post::getNRows(3);

	if(!$posts['count']): ?>
	<div class="post-preview">
		<h2>No recent Posts</h2>
	</div>
<?php
	else:
		while($p = array_shift($posts['posts'])): ?>
	<div class="post-preview">
		<h2><a href="#"><?=$p->Title ?></a></h2>
		<div class="post-summary"><?=$p->Summary ?></div>
	</div>
<?php
		endwhile;
	endif;
?>
</div>

<?php
    include_once('partials/footer.php');
?>