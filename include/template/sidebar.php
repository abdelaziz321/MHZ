                    <div class="sidebar">
                        <div class="bio">
                            <img src="<?= $userPath . $currentProfile->picture; ?>" alt="Profile Picture">
                            <h3>
                                <?= $currentProfile->nick_name; ?>
                            </h3>
                        </div>

                        <div class="menu">
                            <ul>
                                <li><a href="profile.php?id=<?= $_SESSION['user']; ?>"><span class="fa fa-tachometer"></span>Profile</a></li>
                                <li><a href="notifications.php"><span class="fa fa-bell"></span>Notifications</a></li>
                                <li><a href="friends.php?id=<?= $currentProfile->id; ?>"><span class="fa fa-users"></span>Friends</a></li>
                                <li><a href="#"><span class="fa fa-envelope"></span>Messages</a></li>
                                <li><a href="settings.php"><span class="fa fa-cogs"></span>Settings</a></li>
                                <li><a href="?logout"><span class="fa fa-sign-out"></span>Logout</a></li>
                            </ul>
                        </div>
                    </div>
