        <!-- navbar -->
        <div class="navvbar">
            <div class="container">
                <!-- Logo -->
                <div class="logo">
                    <a href="index.php">
                        <img src="<?= $images; ?>logo.png" alt="Logo">
                    </a>
                </div>
                <!-- Search frinds form -->
                <div class="search">
                    <form action="search.php" method="post">
                        <input type="text" name="friend" placeholder="Search for friends" />
                        <button type="submit" name="search"><i class="fa fa-search"></i></button>
                    </form>
                </div>
            </div>
        </div>
