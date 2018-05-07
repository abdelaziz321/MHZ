<?php
$shouldLoggin = true;

require 'core' . DIRECTORY_SEPARATOR . 'init.php';

if (isset($_POST['update_profile'])) {
    # update user profile
    $profile = new Profile($_SESSION['user'], $_POST);
    $profile->updateProfile();

    # update the current profile data
    $currentProfile = (new Profile($_SESSION['user']))->getData();

    # make a new post if the user change his profile picture
    $picture = $profile->getUpdatedProfilePicturePath();
    if ($picture !== false) {
        $post = new Post([
            'caption'     => "{$currentProfile->nick_name} has changed his profile picture",
            'picturePath' => $picture
        ]);
        $post->createProfilePicturePost();
    }
}

?>
            <?php include $templates . 'navbar.php'; ?>

            <div class="container">
                <div class="row wallpaper">
                    <!-- setting page -->
                    <div class="col-md-8">
                        <div class="page">
                            <h3 class="page_header">Update Profile: </h3>
                            <div class="settings_page">

                                <form method="post" enctype="multipart/form-data">

                                    <div class="group">
                                        <div class="input-group input-group-icon">
                                            <input type="text" name="nick_name" placeholder="Nickname" value="<?= $currentProfile->nick_name; ?>" />
                                            <div class="input-icon"><i class="fa fa-user"></i></div>
                                        </div>

                                        <div class="input-group input-group-icon">
                                            <input type="text" name="first_name" placeholder="Firstname" value="<?= $currentProfile->first_name; ?>"/>
                                            <div class="input-icon"><i class="fa fa-user"></i></div>
                                        </div>

                                        <div class="input-group input-group-icon">
                                            <input type="text" name="last_name" placeholder="Lastname" value="<?= $currentProfile->last_name; ?>"/>
                                            <div class="input-icon"><i class="fa fa-user"></i></div>
                                        </div>
                                        <div class="input-group">
                                            <input type="file" name="picture" />
                                            <input type="hidden" name="old_picture" value="<?= $currentProfile->picture; ?>" />
                                        </div>
                                    </div>

                                    <h4>Email And Password: </h4>
                                    <div class="group">
                                        <div class="input-group input-group-icon">
                                            <input disabled readonly name="email" type="email" placeholder="Email Adress" value="<?= $currentProfile->email; ?>"/>
                                            <div class="input-icon"><i class="fa fa-envelope"></i></div>
                                        </div>

                                        <div class="input-group input-group-icon">
                                            <input type="password" name="old_password" placeholder="old password"/>
                                            <div class="input-icon"><i class="fa fa-key"></i></div>
                                        </div>

                                        <div class="input-group input-group-icon">
                                            <input type="password" name="new_password" placeholder="New password"/>
                                            <div class="input-icon"><i class="fa fa-key"></i></div>
                                        </div>

                                        <div class="input-group input-group-icon">
                                            <input type="password" name="confirm_password" placeholder="Confirm password"/>
                                            <div class="input-icon"><i class="fa fa-key"></i></div>
                                        </div>
                                    </div>

                                    <h4>Information</h4>
                                    <div class="group row">
                                        <div class="col-md-6">
                                            <h5>Date of Birth:</h5>
                                            <div class="input-group">
                                                <input type="date" name="dob" value=" value="<?= $currentProfile->data_of_birth; ?>"">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <h5>Gender:</h5>
                                            <div class="input-group">
                                                <input type="radio" name="gender" value="male" id="gender-male" <?= $currentProfile->gender == 'male'? 'checked' : ''; ?> />
                                                <label for="gender-male">Male</label>
                                                <input type="radio" name="gender" value="female" id="gender-female" <?= $currentProfile->gender == 'female'? 'checked' : ''; ?>/>
                                                <label for="gender-female">Female</label>
                                            </div>
                                        </div>
                                    </div>

                                    <h4>Hometown</h4>
                                    <div class="input-group">
                                        <input type="text" name="hometown" placeholder="Hometown"  value="<?= $currentProfile->hometown; ?>"/>
                                    </div>

                                    <h4>About</h4>
                                    <textarea name="about" rows="3" placeholder="About Me" ><?= $currentProfile->about; ?></textarea>

                                    <h4>Relationghip</h4>
                                    <div class="group">
                                        <div class="input-group">
                                            <select name="mstatus">
                                                <option value="single" <?= $currentProfile->marital_status == 'single'? 'selected' : ''; ?> >Single</option>
                                                <option value="engaged" <?= $currentProfile->marital_status == 'engaged'? 'selected' : ''; ?>>Engaged</option>
                                            </select>
                                        </div>
                                        <div class="text-right">
                                            <input type="submit" name="update_profile" value="Update">
                                        </div>
                                    </div>
                                </form>

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
