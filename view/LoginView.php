<?php
class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
        
        private static $controller;

        public function __construct() 
        {
            require_once 'controller/Controller.php';
        }

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
            $message = '';
            $response = '';
            // Let the controller validate the username and password
                        
            self::$controller = new Controller();
            // If the user is logged in
            if(self::$controller->isLoggedIn())
            {
                // If the user has pushed on the logout button
               if($this->ifLogoutButtonPushed())
               {
                   $message = "Bye bye!";
                   $this->generateLoginFormHTML($message);
               }
               // Else show the content for the logged in user
               else
               {
                   $message = "Welcome";
                   $response = $this->generateLogoutButtonHTML($message); 
               }             
            }
            else 
            {
               // If the user has pushed the login button
               if($this->ifLoginButtonPushed())
               {
                   $result = self::$controller->authenticate();
                   $response = $this->getLoginResponse($result);
               }
               // Else show the login form
               else
               { 
                   //echo "response :" . $response;
                   $response = $this->generateLoginFormHTML($message);
               }
            }
            return $response;
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the login form
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
            
		return '
			<form method="post"> 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';      
	}
	
	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	private function getRequestUserName($username) {
		return("?".self::$name."=".$username);
	}
        /*
         * If the user has pushed the login button
         */
        private function ifLoginButtonPushed()
        {
            if(filter_input(INPUT_POST,self::$login)!= NULL)
            {
                unset($_POST[self::$login]);
                return true;        
            }
            return false;
        }
        /*
         * If the user has pushed the logout button
         */
        private function ifLogoutButtonPushed() 
        {
            if(filter_input(INPUT_POST,self::$logout)!= NULL)
            {
                unset($_POST[self::$logout]);
                return true;        
            }
            return false;
        }
        /*
         * Validate the username and password and return the correct response
         */
        private function getLoginResponse($result)
        {
            if($result == "correct")
            {
               return $this->loginResponse(); 
            }
            return $this->generateLoginFormHTML($result);
        }
        /*
         * Return the response for a logged in user
         */
        private function loginResponse()
        {
            $message = "Welcome";
            return $this->generateLogoutButtonHTML($message);
        }
	
}