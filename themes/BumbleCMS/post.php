<?php
    /**
     * Post.php
     * @author Bee Hudson
     */
    include_once('functions.php');

    
    $p = Post::getBySlug($slug[1]);
    if(!$p){ display404(); }
    

    include_once('partials/header.php');
?>
    
<?php
    include_once('partials/footer.php');
?>