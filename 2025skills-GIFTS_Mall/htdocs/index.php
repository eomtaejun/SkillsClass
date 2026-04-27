<?php
    session_start();

    define("BASE_DIR", __DIR__);
    define("APP", BASE_DIR . '/App');
    define("CORE", APP . '/Core');
    define("SOURCE", BASE_DIR . '/resources');
    define("PRODUCTS", BASE_DIR . '/common/products/');

    date_default_timezone_set("Asia/Seoul");

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once CORE . '/ClassLoader.php';

    $Route=new App\Core\Route();

    require_once BASE_DIR . '/routes/web.php';

    $Route->dispatch();