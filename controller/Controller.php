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
    /*
     * Check if the username and password are correct
     */
   public function authenticate()
   {
       $username = $_POST["LoginView::UserName"];
       $password = $_POST["LoginView::Password"];
       require_once 'model/LoginRules.php';
       $loginRules = new LoginRules();
       $resultString = $loginRules->checkLoginInformation($username, $password);
       
       return $resultString;
   }
}
