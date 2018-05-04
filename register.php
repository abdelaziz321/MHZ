<?php

require 'core' . DIRECTORY_SEPARATOR . 'init.php';

// if user is alreadey loggedin
if (User::isLoggedin()) {
    header('LOCATION: ' . BASEURL . 'index.php');
    die;
}

// login form
if (isset($_POST['login'])) {
    $user = new User($_POST);

    if ($user->login()) {
        header('LOCATION: ' . BASEURL . 'index.php');
        die;
    }
    $errors = $_SESSION['errors'];
}

// register form
if (isset($_POST['register'])) {
    $user = new User($_POST);

    if ($user->register()) {
        header('LOCATION: ' . BASEURL . 'index.php');  // may be the update page would be better
        die;
    }
    $errors = $_SESSION['errors'];
}

?>
        <div class="register_page">
            <div class="formbox">
                <?php
                    if (isset($errors)) {
                        print_r($errors);
                    }
                ?>
                <form class="login-form" method="post">

                    <input type="email" name="email" placeholder="email@example.com">
                    <input type="password" name="password" placeholder="password">

                    <input type="submit" name="login" value="Login" >

                    <p class="message">
                          <a href="#">Sign Up ?</a>
                    </p>

                </form>

                <form class="register-form" method="post">

                    <input type="text" name="first_name" placeholder="first name"/>
                    <input type="text" name="last_name" placeholder="last name"/>

                    <input type="email" name="email" placeholder="email@example.com">
                    <input type="password" name="password" placeholder="Password">
                    <input type="password" name="confirm_password" placeholder="Confirm Password">

                    <input type="submit" name="register" value="Sign Up" >

                    <p class="message">
                        <a href="#">Login ?</a>
                    </p>

                </form>
            </div>
        </div>

<?php require $templates . 'footer.php'; ?>
