<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Error extends Controller
{
	/**
	 * @var string
	 */
	protected $_requested_page;

	/**
	 * @var string
	 */
	protected $_message;

	/**
	 * Pre determine error display logic
	 */
	public function before()
	{
	    
		parent::before();

		// Sub requests only!
		if (Request::initial() !== Request::current())
		{
			if ($message = rawurldecode($this->request->param('message')))
			{
				$this->_message = $message;
			}

			if ($requested_page = rawurldecode($this->request->param('origuri')))
			{
				$this->_requested_page = $requested_page;
			}
		}
		else
		{
			// This one was directly requested, don't allow
			$this->request->action(404);

			// Set the requested page accordingly
			$this->_requested_page = Arr::get($_SERVER, 'REQUEST_URI');
		}

		$this->response->status((int) $this->request->action());
	}

	/**
	 * Serves HTTP 404 error page
	 */
	public function action_404()
	{
		
		//$this->template->description = 'The requested page not found';
		//$this->template->keywords = 'not found, 404';
		//$this->template->title = 'Page not found';
		
		$this->view = View::factory('error/404')
			->set('error_message', $this->_message)
			->set('requested_page', $this->_requested_page);
		$this->response->body($this->view );
	}

	/**
	 * Serves HTTP 500 error page
	 */
	public function action_500()
	{
		//$this->template->description = 'Internal server error occured';
		//$this->template->keywords = 'server error, 500, internal error, error';
		//$this->template->title = 'Internal server error occured';

		$this->view = View::factory('error/500');
	}
}
