<?php

session_start();

// database configuration
$_config = [
    'host'      => '127.0.0.1',
    'username'  => 'root',
    'password'  => '!23qwe',
    'database'  => 'MGZ'
];

// general constants
$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
$httpHost = $_SERVER['HTTP_HOST'];
$baseUrl = $httpHost . dirname($_SERVER['SCRIPT_NAME']) . '/';

define('DS', DIRECTORY_SEPARATOR);
define('PATH', substr(__FILE__, 0 , strrpos(__FILE__, 'core')));
define('BASEURL', $protocol . str_replace("//", "/", $baseUrl));


// register classes -autoloading-
$coreClasses = [
    # wall classes
    'Post'      => 'wall' . DS . 'Post',
    'Comment'   => 'wall' . DS . 'Comment',
    'Like'      => 'wall' . DS . 'Like',

    # account classes
    'User'      => 'account' . DS . 'User',
    'Profile'   => 'account' . DS . 'Profile',
    'Friend'    => 'account' . DS . 'Friend',

    # utilities classes
    'DB'        => 'utilities' . DS . 'DB',
    'Validator' => 'utilities' . DS . 'Validator',
    'Photo'     => 'utilities' . DS . 'Photo',

    # others
    'Message'  => 'Message'
];

spl_autoload_register(function($className) use ($coreClasses) {
    require_once 'classes' . DS . $coreClasses[$className] . '.php';
});

// general paths
# assets
$js     = BASEURL . 'layout/js/';
$css    = BASEURL . 'layout/css/';
$photos = BASEURL . 'layout/images/';
# templates
$templates = PATH . 'include' . DS . 'template' . DS;

// check if the user not loggedin
if (isset($shouldLoggin) && !User::isLoggedin()) {
    header('Location: ' . BASEURL .'register.php');
    die;
}

// require the header for all pages
require $templates . 'header.php';
