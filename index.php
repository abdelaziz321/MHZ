<?php

require 'core' . DIRECTORY_SEPARATOR . 'init.php';

# testing the classes

new Post;
echo "<br />";
new Comment;
echo "<br />";
new Like;
echo "<br />";
new User;
echo "<br />";
new Profile;
echo "<br />";
new Friend;
echo "<br />";
new Photo;
echo "<br />";
new Message;
echo "<br />";
$instance = DB::getInstance();


$data = [
    'name'  => 'Ahmed Mouhamed',
    'email' => 'sdsa@gmail.com'
];

$validator = new Validator($data);

$validator->required('name')
    ->minLen('name', 5, 'please, write your name correctly')
    ->required('email')
    ->email('email')
    ->unique('email', ['accounts', 'email', 'id', 1]);

if ($validator->passes()) {
    # do something
}

if ($validator->fails()) {
    $errorsArr = $validator->getMessages();
    print_r($errorsArr);
}
