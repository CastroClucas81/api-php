<?php

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ERROR);


define(HOST, '');
define(DATABASE, '');
define(USER, '');
define(PASSWORD, '');

define(DS, DIRECTORY_SEPARATOR);
define(DIR_APP, __DIR__);
define(DIR_PROJECT, 'api-php'); //nome do projeto

if (file_exists('autoload.php')) {
    include "autoload.php";
} else {
    echo "Erro ao incluir o arquivo env.php";
    exit;
}
