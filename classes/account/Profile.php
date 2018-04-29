<?php


class Profile extends User
{
    /**
     * Calling the parent constructor
     *
     * @param array $data
     */
    function __construct($data)
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

    }

    /**
     * Get all the user data
     *
     * @return stdClass the result
     */
    public function getData()
    {

    }

    /**
     * Get the nickname if provided by the user
     * otherwise get string of the firstname & lastname
     *
     * @return string
     */
    public function getNickname()
    {

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

    }

}
