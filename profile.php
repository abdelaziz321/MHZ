<?php
$shouldLoggin = true;

require 'core' . DIRECTORY_SEPARATOR . 'init.php';

if (!isset($_GET['id']) && empty($_GET['id'])) {
    header('LOCATION: ' . BASEURL . 'index.php');
    die;
}

$profile = new Profile($_GET['id']);
$user = $profile->getData();

$posts = Post::getAccountPosts($_SESSION['user']);

?>
            <?php include $templates . 'navbar.php'; ?>

            <div class="container">
                <div class="row wallpaper">

                    <!-- profile page -->
                    <div class="col-md-8 page">
                        <h3 class="page_header"><?= $user->nick_name; ?>'s Profile</h3>
                        <div class="profile_page">

                            <?php foreach ($posts as $post) { ?>

                            <!-- post -->
                            <div class="post">
                                <header>
                                    <img src="<?= $userPath . 'user1.jpg'; ?>" alt="user"/>
                                    <div>
                                        <h5 class="nickname">
                                            <a href="profile.php?id=<?= $user->id; ?>"><?= $user->nick_name; ?></a>
                                        </h5>
                                        <time><?= $post->created_at ?></time>
                                    </div>
                                </header>
                                <div class="body">
                                    <p><?= $post->caption; ?></p>
                                    <img src="<?= $postPath . $post->image; ?>" />
                                </div>
                                <footer>
                                    <div class="likes">
                                        <span>
                                            <a href="#">
                                                <i class="fa fa-thumbs-up"></i>
                                            </a>
                                            112 Likes
                                        </span>
                                        <span>
                                            <a href="#">
                                                <i class="fa fa-comments"></i> 12 Comments
                                            </a>
                                        </span>
                                    </div>
                                    <div class="comment">
                                        <form action="" method="post">
                                            <textarea name="comment" placeholder="type your comment"></textarea>
                                            <button type="submit" name="submit_comment" title="reply"><i class="fa fa-reply"></i></button>
                                        </form>
                                    </div>
                                </footer>
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
