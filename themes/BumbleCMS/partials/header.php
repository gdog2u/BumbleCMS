<?php
    /**
     * Header.php
     * @author Bee Hudson
     */

    $title = $bumble["site-title"];

    if(isset($p))
    {
        $title = $p->Title." | $title";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?=$title ?></title>
    </head>
    <body>