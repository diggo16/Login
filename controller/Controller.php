<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 *
 * @author Daniel
 */
class Controller {
    private static $loggedIn = false;
    public function __construct() 
    {
        require_once 'model/LoginRules.php';
    }
    /*
     * Check if the username and password are correct
     */
   public function authenticate()
   {
       $username = filter_input(INPUT_POST,"LoginView::UserName",FILTER_SANITIZE_STRING);
       $password = filter_input(INPUT_POST,"LoginView::Password",FILTER_SANITIZE_STRING);
       
       $loginRules = new LoginRules();
       $resultString = $loginRules->checkLoginInformation($username, $password);
       
       return $resultString;
   }
   public function isLoggedIn()
   {
       if(isset($_SESSION["username"]) && isset($_SESSION["password"]))
       {
            $username = $_SESSION["username"];
            $password = $_SESSION["password"];
            $loginRules = new LoginRules();
            $resultString = $loginRules->checkLoginInformation($username, $password);
            if($resultString == "correct")
            {
                return true;
            }
            return false;
       }
       return false;
   }
   public function logout()
   {
       if(filter_input(INPUT_POST,"LoginView::Logout") != NULL)
       {
           return true;
       }
       
       return false;
   }
   public function login()
   {
       self::$loggedIn = true;
   }
}
