<?php

session_start();


// database configuration
$_config = [
    'host'		=> '127.0.0.1',
    'username'	=> 'root',
    'password'	=> '!23qwe',
    'database'	=> 'MGZ'
];


// register classes -autoloading-
define('DS', DIRECTORY_SEPARATOR);

$coreClasses = [
	# wall classes
    'Post'			=> 'wall' . DS . 'Post',
    'Comment'		=> 'wall' . DS . 'Comment',
    'Like'			=> 'wall' . DS . 'Like',

	# account classes
    'User'			=> 'account' . DS . 'User',
    'Profile'		=> 'account' . DS . 'Profile',
    'Friend'		=> 'account' . DS . 'Friend',

	# utilities classes
    'DB'			=> 'utilities' . DS . 'DB',
    'Validator'		=> 'utilities' . DS . 'Validator',
    'Photo'			=> 'utilities' . DS . 'Photo',

	# others
	'Message'		=> 'Message'
];

spl_autoload_register(function($className) use ($coreClasses) {
    require_once 'classes' . DS . $coreClasses[$className] . '.php';
});


// check if the user loggedin
