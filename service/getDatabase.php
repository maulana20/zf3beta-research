<?php
use Zend\Mvc\Application;
use Zend\Config\Reader\Ini;
use Zend\Db\Adapter\Adapter;

chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

// Composer autoloading
include __DIR__ . '/../vendor/autoload.php';

if (! class_exists(Application::class)) {
    throw new RuntimeException(
        "Unable to load application.\n"
        . "- Type `composer install` if you are developing locally.\n"
        . "- Type `vagrant ssh -c 'composer install'` if you are using Vagrant.\n"
        . "- Type `docker-compose run zf composer install` if you are using Docker.\n"
    );
}

$reader = new Ini('config/demo.ini');
$config = $reader->fromFile('config/demo.ini');

$adapter = new Adapter([
	'host' => $config['database']['host'],
    'driver'   => $config['database']['adapter'],
    'database' => $config['database']['dbname'],
    'username' => $config['database']['username'],
    'password' => $config['database']['password'],
]);
var_dump($adapter);
