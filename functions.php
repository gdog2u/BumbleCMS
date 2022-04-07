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
        SELECT TemplateFile
        FROM PostCategories
        WHERE CategoryURIPrefix = ?
    ");

    $get->execute([$slug]);

    $template = $get->fetchColumn(0);
   
    return $template;
}

function display404()
{
    http_response_code(404);
    include(THEME_PATH."errors/404.php");
    exit(0);
}