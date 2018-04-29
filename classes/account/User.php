<?php


class User
{
    /**
     * container of the user data
     *
     * @var array
     */
    private $data;

    /**
     * Database Instance
     *
     * @var DB
     */
    private $_dbInstance;

    /**
     * get database instance and fill the data attribute
     *
     * @param array $data
     */
    function __construct($data)
    {
        $this->_dbInstance = DB::getInstance();
        $this->data = $data;
    }

    /**
     * valdate the given data and check the database with email & password
     *
     * @return bool
     */
    public function login()
    {
        if(!$this->loginValidator()) {
            return false;
        }

        // check the database for the given email
        $this->_dbInstance->select('* FROM accounts WHERE email = :email', [
            ':email'    => $this->data['email']
        ]);

        // if row retrieved and the the hashed password verified
        if ($this->_dbInstance->count() == 1 &&
            password_verify($this->data['password'], $this->_dbInstance->first()->password)) {
            $firstname = $this->_dbInstance->first()->first_name;
            $lastname = $this->_dbInstance->first()->last_name;
            $_SESSION['user'] = "$firstname $lastname";
            return true;
        }

        $_SESSION['errors'][] = 'email or password is wrong';
        return false;
    }

    /**
     * valdate the given data and insert it into the database
     *
     * @return bool
     */
    public function register()
    {
        if (!$this->registerValidator()) {
            return false;
        }

        // insert [firstname, lastname, email, password] into the database
        $this->_dbInstance->insert('INTO accounts SET first_name = :first_name, last_name = :last_name, email = :email, password = :password', [
            ':first_name'   => $this->data['first_name'],
            ':last_name'    => $this->data['last_name'],
            ':email'        => $this->data['email'],
            ':password'     => password_hash($this->data['password'], PASSWORD_DEFAULT)
        ]);

        if ($this->_dbInstance->errors()) {
            $_SESSION['errors'][] = 'can\'t insert';
            return false;
        }

        $firstname = $this->data['first_name'];
        $lastname = $this->data['last_name'];
        $_SESSION['user'] = "$firstname $lastname";
        return true;
    }

    /**
     * logout => destroy the user session
     */
    public static function logout()
    {
        unset($_SESSION["user"]);
    }

    /**
     * Check if the user logged in
     *
     * @return bool
     */
    public static function isLoggedin()
    {
        return isset($_SESSION['user']);
    }

    /**
     * validating the email and passowrd for the login form
     *
     * @return bool
     */
    private function loginValidator()
    {
        $validator = new Validator($this->data);

        $validator->required('email')
                  ->email('email')
                  ->required('password');

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->getMessages();
            return false;
        }
        return true;
    }

    /**
     * validating the [firstname, lastname, email, password] for the register form
     *
     * @return bool
     */
    private function registerValidator()
    {
        $validator = new Validator($this->data);

        $validator->required('first_name')
                  ->minLen('first_name', 3)
                  ->required('last_name')
                  ->minLen('first_name', 3)
                  ->required('email')
                  ->email('email')
                  ->unique('email', ['accounts', 'email'])
                  ->required('password')
                  ->match('password', 'confirm_password');

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->getMessages();
            return false;
        }
        return true;
    }

}
