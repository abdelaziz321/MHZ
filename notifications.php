<?php
$shouldLoggin = true;

require 'core' . DIRECTORY_SEPARATOR . 'init.php';

$accounts = new Friend($_SESSION['user']);

if (isset($_POST['friend_request'])) {
    if ($_POST['friend_request'] == 'accept') {
        $accounts->acceptRequest($_POST['send']);
    } elseif ($_POST['friend_request'] == 'reject') {
        $accounts->rejectRequest($_POST['send']);
    }
}

$accounts = $accounts->getAllfriendRequests();

?>
            <?php include $templates . 'navbar.php'; ?>

            <div class="container">
                <div class="row wallpaper">
                    <!-- friends page -->
                    <div class="col-md-8">
                        <div class="page">
                            <h3 class="page_header">My Notifications: </h3>
                            <div class="notifications_page">
                                <h4>Friend Requests</h4>

                                <?php foreach($accounts AS $account) {
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
                                                <form method="post">
                                                    <input type="hidden" name="send" value="<?= $account->id; ?>">
                                                    <button type="submit" class="btn btn-sm" name="friend_request" value="accept">Accept request</button>
                                                    <button type="submit" class="btn btn-sm" name="friend_request" value="reject">Reject request</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <!-- <h4>General</h4> -->
                            </div>
                        </div>
                    </div>
                    <!-- sidebar -->
                    <div class="col-md-4">

                        <?php include $templates . 'sidebar.php'; ?>

                    </div>
                </div>
            </div>



<?php include $templates . 'footer.php'; ?>
