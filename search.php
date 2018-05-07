<?php
$shouldLoggin = true;

require 'core' . DIRECTORY_SEPARATOR . 'init.php';

$friends = new Friend($_SESSION['user']);

if (isset($_POST['friend_request'])) {
    $friends->sendRequest($_POST['received']);
}

$key = $_POST['friend'] ?: 'all accounts';
$accounts = $friends->searchForFriends($_POST['friend']);

?>
            <?php include $templates . 'navbar.php'; ?>

            <div class="container">
                <div class="row wallpaper">
                    <!-- wall page -->
                    <div class="col-md-8">
                        <h3 class="page_header">Search for '<?= $key; ?>'</h3>
                        <div class="search_page page">
                        <?php foreach ($accounts as $account) {
                            $nickName = Profile::nickName($account);
                            $picture = Profile::profilePicture($account);

                        ?>
                            <div class="account">
                                <img src="<?= $userPath . $picture; ?>" alt="user">
                                <div class="bio">
                                    <h5>
                                        <a href="profile.php?id=<?= $account->id; ?>"><?= $nickName; ?></a>
                                    </h5>
                                    <p>
                                        About: <?= $account->about; ?>
                                    </p>
                                    <div>
                                        <?php
                                            switch ($account->status) {
                                                case 1:
                                                    if ($account->send_id == $_SESSION['user']) {
                                                        ?>
                                                        <span class="label label-warning">Request is sent</span>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <span class="label label-warning">Waiting for your accept</span>
                                                        <?php
                                                    }
                                                    break;

                                                case 2:
                                                    ?>
                                                    <span class="label label-success">Friend</span>
                                                    <?php
                                                    break;

                                                default:
                                                    ?>
                                                    <form method="post">
                                                        <input type="hidden" name="received" value="<?= $account->id; ?>">

                                                        <button type="submit" class="btn btn-sm" name="friend_request">Send request</button>
                                                    </form>
                                                    <?php
                                                    break;
                                            }
                                        ?>

                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                    <!-- sidebar -->
                    <div class="col-md-4">

                        <?php include $templates . 'sidebar.php'; ?>

                    </div>
                </div>
            </div>



<?php include $templates . 'footer.php'; ?>
