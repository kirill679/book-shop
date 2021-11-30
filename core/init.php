<?php

ini_set('session.use_strict_mode', 'on');

const CORE_DIR  = 'core/';
const APP_DIR   = 'app/';
const ADMIN_DIR = APP_DIR . 'admin/';

/* 
    ////////////////////////////////////
    ////// ЭТОТ БЛОК ДЛЯ ОТЛАДКИ //////
    ///////////////////////////////////
*/
set_include_path(
    get_include_path() . PATH_SEPARATOR . CORE_DIR . PATH_SEPARATOR . APP_DIR
    . PATH_SEPARATOR . ADMIN_DIR
);
spl_autoload_extensions('.class.php');
spl_autoload_register();

const ERROR_LOG = ADMIN_DIR . 'error.log';
const ERROR_MSG = 'Срочно обратитесь к администратору! admin@email.info';
function errors_log($msg, $file, $line)
{
    $dt      = date('d-m-Y H:i:s');
    $message = "$dt - $msg in $file:$line\n";
    error_log($message, 3, ERROR_LOG);
    echo ERROR_MSG . "<br>";
}

function error_handler($msg, $file, $line)
{
    errors_log($msg, $file, $line);
}

set_error_handler('error_handler');
function exception_handler($e)
{
    errors_log($e->getMessage(), $e->getFile(), $e->getLine());
}

set_exception_handler('exception_handler');
/* 
    //////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////
*/
const DB
= [
    'HOST' => 'localhost',
    'USER' => 'root',
    'PASS' => 'password',
    'NAME' => 'eshop',
];

ob_start();

session_set_cookie_params(['lifetime' => 3600, 'secure' => true, 'httponly' => true, 'samesite' => 'Strict']);
session_name('SESSION_ID');
session_start();

Eshop::init(DB);
Basket::init();

if (str_contains($_SERVER['REQUEST_URI'], '/admin')) {
    if ( ! Eshop::isAdmin()) {
        header('Location: /enter');
    }
}