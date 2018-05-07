<?php
$shouldLoggin = true;

require 'core' . DIRECTORY_SEPARATOR . 'init.php';

// adding post
if (isset($_POST['post'])) {
    $post = new Post($_POST);
    $post->createPost();
}

$posts = Post::getWallPosts();

?>
            <?php include $templates . 'navbar.php'; ?>

            <div class="container">
                <div class="row wallpaper">
                    <!-- wall page -->
                    <div class="col-md-8">
                        <div class="wall_page page">

                            <!-- post form -->
                            <div class="post_form">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="user" value="<?= $_SESSION['user']; ?>">
                                    <textarea name="caption" placeholder="Say hello world"></textarea>
                                    <div class="post_options">
                                        <div class="post_photo">
                                            <input type="file" name="image" />
                                        </div>
                                        <select name="status">
                                            <option value="1">Only me</option>
                                            <option value="2">Private</option>
                                            <option value="3">Public</option>
                                        </select>
                                        <button type="submit" name="post"><i class="fa fa-send"></i></button>
                                    </div>
                                </form>
                            </div>

                            <?php
                            foreach($posts AS $post) {
                                $nickName = Profile::nickName($post);
                                $picture = Profile::profilePicture($post);
                            ?>

                            <!-- posts -->
                            <div class="post">
                                <header>
                                    <img src="<?= $userPath . $post->picture; ?>" alt="user"/>
                                    <div>
                                        <h5 class="nickname"><a href="#"><?= $post->nick_name; ?></a></h5>
                                        <time><?= $post->created_at; ?></time>
                                    </div>
                                </header>
                                <div class="body">
                                    <p><?= $post->caption; ?></p>
                                    <img src="<?= $postPath . $post->image; ?>" />
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

                            <footer class="see_more">
                                <a href="#">see more</a>
                            </footer>
                        </div>
                    </div>
                    <!-- sidebar -->
                    <div class="col-md-4">

                        <?php include $templates . 'sidebar.php'; ?>

                    </div>
                </div>
            </div>



<?php include $templates . 'footer.php'; ?>
