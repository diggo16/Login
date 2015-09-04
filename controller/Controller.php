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
   public function authenticate($username, $password)
   {   
       $loginRules = new LoginRules();
       $resultString = $loginRules->checkLoginInformation($username, $password);
       return $resultString;
   }
   public function isLoggedIn($username, $password)
   {
       if($this->authenticate($username, $password) == "correct")
       {
           return true;
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
