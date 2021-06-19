<?php
/* Ini overrides*/
ini_set("dispaly_errors", true);
/* Paths */
define("CLASS_PATH", "classes/");
define("UPLOADS_PATH", "uploads/");
define("VIEWS_PATH", "views/");
/* Database */
define("DB_HOST", "localhost");
define("DB_DSN", "mysql:host=".DB_HOST.";dbname=BumbleCMS");
define("DB_USER", "");
define("DB_PASS", "");