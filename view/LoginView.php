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
            $username = filter_input(INPUT_POST,"LoginView::UserName",FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST,"LoginView::Password",FILTER_SANITIZE_STRING);
            /*
             * If the logout button is pushed
             */
            if($this->ifLogoutButtonPushed())
            {
                   return $this->logout("Bye bye!");
            }
            /*
             * If correct cookies is set
             */
            if($this->ifCookiesLoggedIn())
            {
                return $this->loginResponse();
            }
            /*
             * If you have pushed the login button
             */
            if($this->ifLoginButtonPushed())
            {   
                $this->keepLoggedIn(); 
                $result = self::$controller->authenticate($username, $password);
                $response = $this->getLoginResponse($result);
                return $response;
            }
            /*
             * If you already are logged in
             */
            if(self::$controller->isLoggedIn($username, $password))
            {
                echo "already logged in";
                   $message = "Welcome";
                   $response = $this->generateLogoutButtonHTML($message); 
                   return $this->loginResponse();             
            }
            else 
            {
               // If the user has pushed the login button
               if($this->ifLoginButtonPushed())
               {   
                   $result = self::$controller->authenticate($username, $password);
                   $response = $this->getLoginResponse($result);
               }
               // Else show the login form
               else
               { 
                   $response = $this->logout("");
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
                unset($_POST[self::$keep]);
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
               $this->keepLoggedIn();
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
        /*
         * Check if the user want to keep to be logged in or not
         */
        private function keepLoggedIn()
        {
            $keepLoggedIn = filter_input(INPUT_POST,self::$keep);
            /*
             * if it is set, save the username and password in the objects
             * cookieName and cookiePassword
             */
            if($keepLoggedIn != NULL)
            {
                $username = filter_input(INPUT_POST,self::$name);
                $password = filter_input(INPUT_POST,self::$password);
                setcookie(self::$cookieName, $username, time() + (86400 * 1), "/"); // 86400 = 1 day
                setcookie(self::$cookiePassword, $password, time() + (86400 * 1), "/"); // 86400 = 1 day
            }
            /*
             * Else remove the saved data if there is any
             */
            else
            {
                setcookie(self::$cookieName, "", time() - 3600);
                setcookie(self::$cookiePassword, "", time() - 3600);
            }
        }
        private function ifCookiesLoggedIn()
        {
            $cookieName = filter_input(INPUT_COOKIE, self::$cookieName);
            $cookiePassword = filter_input(INPUT_COOKIE, self::$cookiePassword);
            
            if(isset($cookieName) && isset($cookiePassword))
            {
                $result = self::$controller->authenticate($cookieName, $cookiePassword);
                if($result == "correct")
                {
                    return true;
                }
            }
            return false;
        }
        private function deleteCookies()
        {
            $cookieName = filter_input(INPUT_COOKIE, self::$cookieName);
            $cookiePassword = filter_input(INPUT_COOKIE, self::$cookiePassword);
            if(isset($cookieName))
            {
                setcookie(self::$cookieName, null, -1, '/');
            }
            if(isset($cookiePassword))
            {
                setcookie(self::$cookiePassword, null, -1, '/');
            }
        }
        private function logout($message)
        {
            $this->deleteCookies();  
            $response = $this->generateLoginFormHTML($message);
            return $response;
        }
}