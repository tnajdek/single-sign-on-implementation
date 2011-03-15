<?php defined('SYSPATH') or die('No direct script access.');
/** TODO: refactor to use HMVC, display appripriate login dialog **/
/** TODO: separate openid column into google and yahoo **/
/** TODO: implement email detection (for accounts with the same email and different association **/
/** TODO: general clean up **/
/** TODO: logout mechanism **/
require APPPATH.'/lib/openidextra.php';
require APPPATH.'/lib/facebook.php';
require APPPATH.'/lib/twitteroauth/twitteroauth.php';
require APPPATH.'/config/oauth.php';


class Controller_Auth extends Controller {
	public function action_index()
	{
	    $openid = new LightOpenID;
	    if(isset($_POST['open-id-identifier'])) {
		$openid->identity = $_POST['open-id-identifier'];
		$openid->returnUrl = self::getReturnTo("finish");
		$this->request->redirect($openid->authUrl());
		return;
	    } else {
		$test = new View('index');
		$this->response->body($test);
	    }
	}
	
	public function action_google() {
		$openid = new LightOpenIDExtra;
		$openid->identity = 'https://www.google.com/accounts/o8/id';
		$openid->returnUrl = self::getReturnTo("finish");
		$openid->required = array('namePerson/friendly', 'contact/email');
		$this->request->redirect($openid->authUrl());
	}
	
	public function action_yahoo() {
		$openid = new LightOpenID;
		$openid->identity = 'http://me.yahoo.com/';
		$openid->returnUrl = self::getReturnTo("finish");
		$this->request->redirect($openid->authUrl());
	}
	
	public function action_facebook() {
		$fb = $this->getFacebook();
		if(!$fb->getSession()) {
			$this->request->redirect(
				$fb->getLoginUrl(array(
				      'req_perms'=>'email',
				      'cancel_url'=>self::getReturnTo("cancel"),
				      'next'=>self::getReturnTo("fbfinish")
				))
			);
		} else {
			$this->request->redirect('/auth/fbfinish');
		}
	}
	
	public function action_fbfinish() {
		
		$fb = $this->getFacebook();
		
		try {
			$me = $fb->api('/me');
		} catch (FacebookApiException $e) {
			$view = new View('auth_finish');
			$view->message = "Unable to confirm identity using Facebook";
			$this->response->body($view);
			return;
		}
		$view = $this->processUser($me['id'], 'facebook');
		$this->response->body($view);
	}
	
	public function action_twitter() {
		Session::instance()->delete('oauth_token');
		Session::instance()->delete('oauth_token_secret');
		$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET); 
		$request_token = $connection->getRequestToken($this->getReturnTo('twfinish'));
		Session::instance()->set('oauth_token', $request_token['oauth_token']);
		Session::instance()->set('oauth_token_secret', $request_token['oauth_token_secret']);
		
		if($connection->http_code == 200) {
			$url = $connection->getAuthorizeURL($request_token['oauth_token']);
			$this->request->redirect($url);
		}
		else {
			$view = new View('auth_finish');
			$view->message = "Unable to confirm identity using Twitter";
			//$view->showform = true;
			$this->response->body($view);
		}
		return;
	}
	
	public function action_twfinish() {
		if (isset($_REQUEST['oauth_token']) &&
		    Session::instance()->get('oauth_token', NULL) !== $_REQUEST['oauth_token']) {
			Session::instance()->set('oauth_token', 'oldtoken');
			$this->request->redirect('/');
		}
		$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY,
					       TWITTER_CONSUMER_SECRET,
					       Session::instance()->get('oauth_token', NULL),
					       Session::instance()->get('oauth_token_secret', NULL)
					       );
		$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
		//$_SESSION['access_token'] = $access_token;
		Session::instance()->delete('oauth_token');
		Session::instance()->delete('oauth_token_secret');
		if (200 == $connection->http_code) {
			$view = $this->processUser($access_token['user_id'], 'twitter');
			$this->response->body($view);
		} else {
			$view = new View('auth_finish');
			$view->message = "Unable to confirm identity using Twitter";
			//$view->showform = true;
			$this->response->body($view);
			return;
		}
	}
	
	public function action_finish() {
		$openid = new LightOpenID;
		
		if($openid->mode == 'cancel') {
			$view = new View('auth_finish');
			$view->message = "The authorisation procedure has been cancelled.";
			//$view->showform = true;
		} else if($openid->validate()) {
			$view = $this->processUser($openid->identity, 'openid');
		} else {
			$view = new View('auth_finish');
			$view->message = "Login failed. Would you like to try again?";
			//$view->showform = true;
		}
	$this->response->body($view);
	}
	
	//refactor me
	public function action_classic() {
		if(
			(!isset($_POST['email']) || strlen($_POST['email'])==0 ) 
			||
			(!isset($_POST['password']) || strlen($_POST['password'])==0 )
			) {
			$view = new View('auth_finish');
			$view->message = "Please provide email and password";
			$this->response->body($view);
			return;
		}
		if($_POST['action'] == 'Sign up for an account') {
			$assoc = ORM::factory('association')
				->where('email', '=', $_POST['email'])->find();
			if(isset($assoc->id)) {
				if(md5($_POST['password']) == $assoc->password) {
					$view = new View('auth_finish');
					$view->message = "Login successful. Welcome back ".$assoc->user->getDisplayName();
					$this->response->body($view);
				} else {
					if(strlen($assoc->password)==0) {
						$view = new View('auth_finish');
						$view->message = "We're sorry but this email account has already been associated with an account using a third party provider.";
						$this->response->body($view);
					} else {
						$view = new View('auth_finish');
						$view->message = "We're sorry but this email account has already been registered and password entered did not match.";
						$this->response->body($view);
					}
				}
			} else {
				$view = $this->processUser(md5($_POST['password']),
							       'password',
							       array('email'=>$_POST['email'])
							       );
				$this->response->body($view);
			}
		} else {
			$assoc = ORM::factory('association')
				->where('email', '=', $_POST['email'])
				->where('password', '=', md5($_POST['password']))
				->find();
			if(isset($assoc->id)) {
				$view = new View('auth_finish');
				$view->message = "Login successful. Welcome back ".$assoc->user->getDisplayName();
				$this->response->body($view);
			} else {
				$view = new View('auth_finish');
				$view->message = "Login Failed. Please try again";
				$this->response->body($view);
			}
		}
	}
	
	public static function getScheme() {
		$scheme = 'http';
		if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
		    $scheme .= 's';
		}
		return $scheme;
	}

    /**
     * Answers the URL we wish for the identity provider to return
     * a user to once the authentication process is finished.
     * 
     */
    public static function getReturnTo($action = 'finish') {
        return sprintf("%s://%s/auth/%s",
                self::getScheme(), $_SERVER['SERVER_NAME'], $action);
    }
    

    /**
     * Answers the trust root for this domain.
     * 
     */
    public static function getTrustRoot() {
        return sprintf("%s://%s/", self::getScheme(), $_SERVER['SERVER_NAME']);
    }
    
    private function getFacebook() {
	return new Facebook(array(
		    'appId'  => FACEBOOK_APP_ID,
		    'secret' => FACEBOOK_SECRET,
		    'cookie' => true,
		));
    }
    
    //TODO email reckognition, user multi-association
    private function processUser($identifier, $identifierKey, $extra = array()) {
	$view = new View('auth_finish');
	if(isset($extra['email'])) {
		$assoc = ORM::factory('association')
		->where($identifierKey, '=', $identifier)
		->where('email', '=', $extra['email'])
		->find();
	} else {
		$assoc = ORM::factory('association')
		->where($identifierKey, '=', $identifier)
		->find();
	}
	if(isset($assoc->id)) {
		$view->message = "Login successful. Welcome back ".$assoc->user->getDisplayName();
	} else {
		if(isset($extra['email'])) {
			$assoc = ORM::factory('association')
			->where('email', '=', $extra['email'])
			->find();
		}
		if(isset($assoc->id)) {
			$view->message = 'Email address '.$_POST['email'].' is already in use';
			return $view;
		}
		$user = ORM::factory('user');
		$user->save();
		$assoc = ORM::factory('association');
		$assoc->user_id = $user->id;
		if(isset($extra['email'])) {
			$assoc->email = $extra['email'];
		}
		$assoc->$identifierKey = $identifier;
		$assoc->save();
		$view->message = "Login successful. New user registered.";
	}
	$view->showform = false;
	return $view;
    }
}



?>
