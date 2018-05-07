<?php


class Profile extends User
{

    /**
     * the current user id
     *
     * @var int
     */
    private $userId;

    /**
     * the updated Profile picture
     * used to create the 'user has change his profile picture' post
     *
     * @var string
     */
    private $updatedProfilePicture;

    /**
     * Path of the post's image
     *
     * @var string
     */
    private $picturePath = PATH . 'uploads' . DS . 'users' . DS;

    /**
     * assign the current user id
     *
     * @param int $id
     */
    function __construct($id, $data = null)
    {
        if (!empty($data)) {
            parent::__construct($data);
        }

        $this->userId = $id;
    }

    /**
     * Update user profile using the 'info' array
     * [firstname, lastname, nickname, email ...]
     *
     * @param array info
     * @return bool
     */
    public function updateProfile()
    {
        $profilePicture = new UploadedFile('picture');

        // validating user info
        if (!$this->validateInfo($profilePicture)) {
            return false;
        }

        $params = [
            ':id'       => $this->userId,
            ':nname'    => $this->data['nick_name'],
            ':fname'    => $this->data['first_name'],
            ':lname'    => $this->data['last_name'],
            ':hometown' => $this->data['hometown'],
            ':about'    => $this->data['about'],
            ':mstatus'  => $this->data['mstatus'],
            ':gender'   => $this->data['gender']
        ];

        // update the user password if provided
        $passwordField = '';
        if (!empty($this->data['old_password'])) {
            $passwordField = 'password       = :pass,';
            $params[':pass'] = password_hash($this->data['password'], PASSWORD_DEFAULT);
        }

        // update the profile picture if a picture provider
        $pictureField = '';
        if ($profilePicture->isExists() && $profilePicture->moveTo($this->picturePath)) {
            $pictureField = 'picture          = :picture,';
            $params[':picture'] = $profilePicture->getNewFileName();

            $this->updatedProfilePicture = $profilePicture->getNewFileName();

            # delete the pervious profile picture
            if (!empty($this->data['old_picture']) &&
                $this->data['old_picture'] != 'default-male.png' &&
                $this->data['old_picture'] != 'default-female.png') {
                UploadedFile::removeFrom($this->picturePath, $this->data['old_picture']);
            }
        }

        // update the data of birth if provider
        $dobField = '';
        if (!empty($this->data['dob'])) {
            $dobField = 'data_of_birth  = :dob,';
            $params[':dob'] = $this->data['dob'];
        }

        // start connecting
        $db = DB::getInstance();
        $sql = "accounts
                    SET
                        nick_name      = :nname,
                        first_name     = :fname,
                        last_name      = :lname,
                        $pictureField
                        $passwordField
                        $dobField
                        hometown       = :hometown,
                        about          = :about,
                        marital_status = :mstatus,
                        gender         = :gender
                    WHERE
                        id = :id";


        $db->update($sql, $params);

        if ($db->errors()) {
            $_SESSION['errors'][] = 'can\'t update profile';
            return false;
        }
        return true;
    }

    /**
     * Get all the user data
     *
     * @return stdClass the result
     */
    public function getData()
    {
        $db = DB::getInstance();
        $db->select('* FROM accounts WHERE id = :id', [
            ':id' => $this->userId
        ]);

        $user = $db->first();

        $user->nick_name = self::nickName($user);
        $user->picture = self::profilePicture($user);

        return $user;
    }

    /**
     * Get the path of the updated profile picture
     *
     * @return mixed
     */
    public function getUpdatedProfilePicturePath()
    {
        if (empty($this->updatedProfilePicture)) {
            return false;
        }
        return "{$this->picturePath}{$this->updatedProfilePicture}";
    }

    /**
     * Get the name of the picture if exists
     * otherwise return the default avatar
     *
     * @param stdClass $profile
     * @return string
     */
    public static function profilePicture($profile)
    {
        if (!empty($profile->picture)) {
            return $profile->picture;
        }

        if (empty($profile->gender)) {
            return 'default-male.png';
        }

        return "default-{$profile->gender}.png";
    }

    /**
     * Get the nick_name if exists
     * otherwise return 'first + last' name
     *
     * @param stdClass $account
     * @return string
     */
    public static function nickName($profile)
    {
        return $profile->nick_name ?: "$profile->first_name $profile->last_name";
    }

    /**
     * validating the user inforamation before updating
     *
     * @param UploadedFile $profilePicture
     * @return bool
     */
    private function validateInfo($profilePicture)
    {
        $validator = new Validator($this->data);

        // validate basic inforamation
        $validator->required('first_name')
                  ->minLen('first_name', 3)
                  ->maxLen('first_name', 9)
                  ->required('last_name')
                  ->minLen('last_name', 3)
                  ->maxLen('last_name', 9);

        // validate password if provided
        if (!empty($this->data['old_password'])) {
            $validator->required('new_password')
                      ->required('confirm_password')
                      ->match('new_password', 'confirm_password');
        }

        // validate profilePicture if uploaded
        if ($profilePicture->isExists()) {
            $validator->image('picture');
        }

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->getMessages();
            return false;
        }
        return true;
    }

}
