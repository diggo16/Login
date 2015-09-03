<?php
/**
 * Description of LoginRules
 *
 * @author Daniel
 */
class LoginRules {
    private static $usernameMissing = 'Username is missing';
    private static $passwordMissing = 'Password is missing';
    private static $wrongText = "Wrong name or password";
    private static $correct = 'correct';
    private static $correctUser;
    public function checkLoginInformation($username, $password)
    {
        // Include the class User.php and creates the "correct" user
        require_once 'model/User.php';
        $correctUsername = "admin";         // Correct username
        $correctPassword = "password";      // Correct password
        self::$correctUser = new User($correctUsername, $correctPassword);
        //Return error message if the username is empty
        if($this->missingUsername($username))
        {
           return self::$usernameMissing; 
        }
        //Return error message if the username is empty
        if($this->missingPassword($password))
        {
           return self::$passwordMissing; 
        }
        // If the username and password match return a correct string else error string
        if($username == self::$correctUser->getUsername())
        {
            if($password == self::$correctUser->getPassword())
            {
                return self::$correct;
            }
            return self::$wrongText;
        }
        else
        {
           return self::$wrongText;
        }
    }
    /*
     * Check if the username is empty, return boolean
     */
    private function missingUsername($username)
    {
        if($username == "")
        {
            return TRUE;
        }
        return FALSE;
    }
     /*
     * Check if the password is empty, return boolean
     */
    private function missingPassword($password)
    {
        if($password == "")
        {
            return TRUE;
        }
        return FALSE;
    }
}
