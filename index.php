<?php
$shouldLoggin = true;

require 'core' . DIRECTORY_SEPARATOR . 'init.php';

// adding post
if (isset($_POST['post'])) {
    $post = new Post($_POST);
    $post->createPost();
}

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
                                            <option value="0">Only me</option>
                                            <option value="1">Private</option>
                                            <option value="2">Public</option>
                                        </select>
                                        <button type="submit" name="post"><i class="fa fa-send"></i></button>
                                    </div>
                                </form>
                            </div>

                            <!-- posts -->
                            <div class="post">
                                <header>
                                    <img src="<?= $userPath . 'user1.jpg'; ?>" alt="user"/>
                                    <div>
                                        <h5 class="nickname"><a href="#">Abdelaziz Sliem</a></h5>
                                        <time>2 houres ago</time>
                                    </div>
                                </header>
                                <div class="body">
                                    <p>
                                        Be faithful in small things because it is in them that your strength lies.
                                    </p>
                                    <img src="<?= $postPath . 'post1.jpg'; ?>" />
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

                            <!-- posts -->
                            <div class="post">
                                <header>
                                    <img src="<?= $userPath . 'user2.jpg'; ?>" alt="user"/>
                                    <div>
                                        <h5 class="nickname"><a href="#">Hossam Ghanem</a></h5>
                                        <time>3 houres ago</time>
                                    </div>
                                    <form>
                                        <input type="hidden" name="post" />
                                        <button type="submit" name="delete_post"><i class="fa fa-trash"></i></button>
                                    </form>
                                </header>
                                <div class="body">
                                    <p>
                                        The sky takes on shades of orange during sunrise and sunset, the colour that gives you hope that the sun will set only to rise again.
                                    </p>
                                    <img src="<?= $postPath . 'post2.jpg'; ?>" />
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

                            <!-- posts -->
                            <div class="post">
                                <header>
                                    <img src="<?= $userPath . 'user3.jpg'; ?>" alt="user"/>
                                    <div>
                                        <h5 class="nickname"><a href="#">Marawan Mostafa</a></h5>
                                        <time>5 houres ago</time>
                                    </div>
                                </header>
                                <div class="body">
                                    <p>
                                        Some days are just bad days, that's all. You have to experience sadness to know happiness, and I remind myself that not every day is going to be a good day, that's just the way it is!
                                    </p>
                                    <img src="<?= $postPath . 'post3.jpg'; ?>" />
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
