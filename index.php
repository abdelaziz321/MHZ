<?php
$shouldLoggin = true;

require 'core' . DIRECTORY_SEPARATOR . 'init.php';

if (isset($_GET['logout'])) {
    User::logout();
    header('LOCATION: ' . BASEURL . 'register.php');
    die;
}

?>
<h1>hello, <?= $_SESSION['user']; ?></h1>
<a href="?logout">logout</a>
