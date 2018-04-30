<?php


class Profile extends User
{
    /**
     * Calling the parent constructor
     *
     * @param array $data
     */
    function __construct($data = null)
    {
        parent::__construct($data);
    }

    /**
     * Update user profile using the 'info' array
     * [firstname, lastname, nickname, email ...]
     *
     * @param array info
     */
    public static function updateProfile($info)
    {
        if (!$this->validateInfo()) {
            return false;
        }

        $params = [
            ':fname'    => $this->data['first_name'],
            ':lname'    => $this->data['last_name'],
            ':email'    => $_SESSION['user'],
            ':pass'     => password_hash($this->data['password'], PASSWORD_DEFAULT),
            ':niname'   => $this->data['nick_name'],
            ':photo'    => $this->data['photo'],
            ':gender'   => $this->data['gender'],
            ':about'    => $this->data['about'],
            ':hometown' => $this->data['hometown'],
            ':dob'      => $this->data['dob'],
            ':Mstatus'  => $this->data['Mstatus']
        ];

        // insert [firstname, lastname, email, password] into the database
        $this->_dbInstance->update('accounts
                                        SET
                                            first_name     = :fname,
                                            last_name      = :lname,
                                            password       = :pass,
                                            nick_name      = :nname,
                                            photo          = :photo,
                                            gender         = :gender,
                                            about          = :about,
                                            hometown       = :hometown,
                                            data_of_birth  = :dob,
                                            marital_status = :Mstatus
                                        WHERE
                                            email = :email', $params);

        if ($this->_dbInstance->errors()) {
            $_SESSION['errors'][] = 'can\'t update';
            return false;
        }

        $firstname = $this->data['first_name'];
        $lastname = $this->data['last_name'];
        $_SESSION['user'] = "$firstname $lastname";
    }

    /**
     * Get all the user data
     *
     * @return stdClass the result
     */
    public function getData($email)
    {
        $dbInstance = DB::getInstance();
        $user = $dbInstance->select('* FROM accounts WHERE email = :email');

        if (empty($user->nick_name)) {
            $user->nick_name = "$user->first_name $user->last_name";
        }

        return $user;
    }

    /**
     * Get the nickname if provided by the user
     * otherwise get string of the firstname & lastname
     *
     * @param string $email
     * @return string
     */
    public function getNickname($email)
    {
        $dbInstance = DB::getInstance();
        $user = $dbInstance->select('* FROM accounts WHERE email = :email');
        if (empty($user->nick_name)) {
            return "$user->first_name $user->last_name";
        }
        return $user->nick_name;
    }

    /**
     * Upldoad the picture and Generate new name for the profile picture
     */
    private function profilePicture()
    {

    }

    /**
     * validating the user inforamation before updating
     *
     * @return bool
     */
    private function validateInfo()
    {
        $validator = new Validator($this->data);

        $validator->required('first_name')
                  ->minLen('first_name', 3)
                  ->maxLen('first_name', 9)
                  ->required('last_name')
                  ->minLen('last_name', 3)
                  ->maxLen('last_name', 9)
                  ->required('password')
                  ->match('password', 'confirm_password')
                  ->minLen('nick_name', 3)
                  ->maxLen('nick_name', 15);

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->getMessages();
            return false;
        }
        return true;
    }

}
