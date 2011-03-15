<?php defined('SYSPATH') or die('No direct script access.');

require APPPATH.'/lib/openid.php';

class Controller_Login extends Controller {

	public function action_index()
	{
		//$db = Database::instance();
		Session::instance()->destroy();
		//var_dump($object);
		$test = new View('rich');
		$this->response->body($test);
	}
	
	public function action_pure() {
		$test = new View('pure');
		$this->response->body($test);
	}

} // End Welcome
