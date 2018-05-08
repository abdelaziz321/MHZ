<?php
$shouldLoggin = true;

require 'core' . DIRECTORY_SEPARATOR . 'init.php';

if (!isset($_GET['id']) && empty($_GET['id'])) {
    header('LOCATION: ' . BASEURL . 'index.php');
    die;
}

$posts = Post::getAccountPosts($_GET['id']);
$profile = (new Profile($_GET['id']))->getData();

// determine if the current user is a friend of the owner of that profile
$friend = (new Friend($currentProfile->id))->isFriend($_GET['id']);

?>
            <?php include $templates . 'navbar.php'; ?>

            <div class="container">
                <div class="row wallpaper">

                    <!-- profile page -->
                    <div class="col-md-8 page">
                        <h3 class="page_header"><?= $profile->nick_name; ?>'s Profile</h3>
                        <div class="profile_page">

                        <?php
                        foreach ($posts as $post) {
                            $nickName = Profile::nickName($post);
                            $picture = Profile::profilePicture($post);
                            list($status, $statusClass) = Post::getPostStatus($post);

                            // if the post is only me and the current user is not the owner of the post
                            if ($post->status == 1 && $currentProfile->id != $post->account_id) {
                                continue;
                            }

                            // if the post is private and the current user is not the owner of the post or a friend
                            if ($post->status == 2 && $currentProfile->id != $post->account_id && !$friend) {
                                continue;
                            }

                        ?>
                            <!-- post -->
                            <div class="post">
                                <header>
                                    <img src="<?= $userPath . $picture; ?>" alt="user"/>
                                    <div>
                                        <h5 class="nickname">
                                            <a href="profile.php?id=<?= $post->account_id; ?>"><?= $post->nick_name; ?></a>
                                        </h5>
                                        <time><?= $post->created_at ?></time>
                                        <span class="label label-<?= $statusClass ?>"><?= $status; ?> post</span>
                                    </div>
                                </header>
                                <div class="body">
                                    <p><?= $post->caption; ?></p>
                                    <?php if (!empty($post->image)): ?>
                                        <img src="<?= $postPath . $post->image; ?>" />
                                    <?php endif; ?>
                                </div>
                                <!-- <footer>
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
                                </footer> -->
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
