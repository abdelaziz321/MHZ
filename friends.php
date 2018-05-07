<?php
$shouldLoggin = true;

require 'core' . DIRECTORY_SEPARATOR . 'init.php';

$friend = new Friend($_GET['id']);

// unfriend using the 'unfriend button' for each user
if (isset($_POST['unfriend'])) {
    $friend->unfriend($_POST['friend']);
}

// get all friends of the current users
$friends = $friend->getAllFriends();


// TODO: show the friends'friedns using $_GET['id']

?>
            <?php include $templates . 'navbar.php'; ?>

            <div class="container">
                <div class="row wallpaper">
                    <!-- friends page -->
                    <div class="col-md-8">
                        <div class="page">
                            <h3 class="page_header">My Friends: </h3>
                            <div class="frinds_page">

                                <?php if (empty($friends)) {  ?>
                                    <p>You have no friends :'(</p>
                                <?php } ?>

                                <?php
                                    foreach ($friends as $friend) {
                                        $nickName = Profile::nickName($friend);
                                        $picture = Profile::profilePicture($friend);
                                ?>

                                <div class="card">
                                    <img src="<?= $userPath . $picture; ?>" alt="user">
                                    <div>
                                        <h5><a href="profile.php?id=<?= $friend->id; ?>"><?= $nickName; ?></a></h5>
                                        <form method="post">
                                            <input type="hidden" name="friend" value="<?= $friend->id; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" name="unfriend">Unfriend</button>
                                        </form>
                                    </div>
                                </div>

                                <?php } ?>
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
