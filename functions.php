<?php

function loadSiteSettings()
{
    global $dbh;

    $options = [];
    $get = $dbh->prepare("
        SELECT OptionName, OptionValue
        FROM BumbleOptions
    ");

    $get->execute();
    while($option = $get->fetch())
    {
        $options[$option['OptionName']] = $option['OptionValue'];
    }

    $options['theme-path'] = THEME_PATH.$options['active-theme-path']."/";

    return $options;
}

function getSlugFromURI(): array
{
    $uri = preg_split("/\//", $_SERVER["REQUEST_URI"], -1, PREG_SPLIT_NO_EMPTY);

    return $uri;
}

function getSlugTemplate(string $slug): string
{
    global $dbh;

    $template = "";

    $get = $dbh->prepare("
        SELECT TemplatePath
        FROM RewriteEngine
        WHERE URIPath = ?
    ");

    $get->execute([$slug]);

    $template = $get->fetchColumn(0);
   
    return $template;
}

function display404(): never
{
    global $bumble;
    
    include($bumble['theme-path']."errors/404.php");
    exit(0);
}