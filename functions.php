<?php

function getSlugFromURI(): array
{
    $uri = preg_split("/\//", $_SERVER["REQUEST_URI"], -1, PREG_SPLIT_NO_EMPTY);

    return $uri;
}

function getSlugTemplate(string $slug): string
{
    global $dbh;
   
    return "";
}

function display404()
{
    http_response_code(404);
    include(VIEWS_PATH."errors/404.php");
    exit(0);
}