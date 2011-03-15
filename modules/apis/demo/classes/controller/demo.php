<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Demo extends Controller {

	/**
	 * @var  string  API
	 */
	protected $api;

	/**
	 * @var  string  demo name
	 */
	protected $demo;

	/**
	 * @var  string  demo source code
	 */
	protected $code;

	/**
	 * @var  string  demo content
	 */
	protected $content;

	/**
	 * @var  object  OAuth_Provider
	 */
	protected $provider;

	/**
	 * @var  object  OAuth_Consumer
	 */
	protected $consumer;

	/**
	 * @var  object  OAuth_Token
	 */
	protected $token;

	public function before()
	{
		$this->request->response = View::factory('api/demo')
			->bind('code', $this->code)
			->bind('content', $this->content)
			->bind('api', $this->api)
			->bind('demo', $this->demo)
			->bind('demos', $demos)
			;

		// Load the cookie session
		$this->session = Session::instance('cookie');

		// Get the name of the demo from the class name
		$provider = strtolower($this->api = preg_replace('/^Controller_(.+)_Demo$/i', '$1', get_class($this)));

		// Load the provider
		$this->provider = OAuth_Provider::factory($provider);

		// Load the consumer
		$this->consumer = OAuth_Consumer::factory(Kohana::config("oauth.{$provider}"));

		if ($token = $this->session->get($this->key('access')))
		{
			// Make the access token available
			$this->token = $token;
		}

		// Start reflection to get the demo list
		$class   = new ReflectionClass($this);
		$methods = $class->getMethods();

		$demos = array();
		foreach ($methods as $method)
		{
			if (preg_match('/^demo_(.+)$/i', $method->name, $matches))
			{
				// Set the demo name
				$demo = $matches[1];

				// Add the demo link
				$demos[$demo] = $this->request->uri(array('action' => 'api', 'id' => strtolower($demo)));
			}
		}

		return parent::before();
	}

	public function action_index()
	{
		$this->content = View::factory('api/index');
	}

	public function action_api($method)
	{
		// Set the demo name
		$this->demo = $method;

		// Start reflection
		$method = new ReflectionMethod($this, "demo_{$method}");

		try
		{
			// Invoke the method to create content
			$method->invoke($this);
		}
		catch (Exception $e)
		{
			// Start buffering
			ob_start();

			// Render the exception
			Kohana::exception_handler($e);

			// Capture the exception HTML
			$this->content = ob_get_clean();
		}

		// Get the source code for this method
		$this->code = $this->source($method->getFilename(), $method->getStartLine(), $method->getEndLine());
	}

	public function after()
	{
		$this->request->response->title = $this->api.($this->demo ? ": {$this->demo}" : '');

		return parent::after();
	}

	public function key($name)
	{
		return "demo_{$this->provider->name}_{$name}";
	}

	public function source($file, $start, $end)
	{
		// Do not include the function definition
		$start++;
		$end--;

		// Open the file for reading
		$handle = fopen($file, 'r');

		// Starting line number
		$line = 0;

		$source = '';
		while ($row = fgets($handle))
		{
			if ($line++ < $start)
			{
				continue;
			}

			if ( ! isset($space))
			{
				// Find indentation whitespace of the first row
				preg_match('/^\s+/', $row, $matches);

				// Get the amount of space to find
				$space = isset($matches[0]) ? $matches[0] : FALSE;
			}

			if ($space)
			{
				if (substr($row, 0, strlen($space)) === $space)
				{
					// Remove indentation whitespace
					$row = substr($row, strlen($space));
				}
			}

			// Add this row to the source
			$source .= $row;

			if ($line >= $end)
			{
				break;
			}
		}

		// Source may include HTML, escape it
		$source = HTML::chars($source);

		// Set the source location
		$location = Kohana::debug_path($file);
		$location = "{$location} [ {$start} - {$end} ]";

		return "<aside>{$location}</aside>\n<pre><code>{$source}</code></pre>";
	}

	public function demo_login()
	{
		// Attempt to complete signin
		if ($verifier = Arr::get($_REQUEST, 'oauth_verifier'))
		{
			if ( ! $token = $this->session->get($this->key('request')) OR $token->token !== Arr::get($_REQUEST, 'oauth_token'))
			{
				// Token is invalid
				$this->session->delete($this->key('request'));

				// Restart the login process
				$this->request->redirect($this->request->uri);
			}

			// Store the verifier in the token
			$token->verifier($verifier);

			// Exchange the request token for an access token
			$token = $this->provider->access_token($this->consumer, $token);

			// Store the access token
			$this->session->set($this->key('access'), $token);

			// Request token is no longer needed
			$this->session->delete($this->key('request'));

			// Refresh the page to prevent errors
			$this->request->redirect($this->request->uri);
		}

		if ($this->token)
		{
			// Login succesful
			$this->content = Kohana::debug('Access token granted:', $this->token);
		}
		else
		{
			// We will need a callback URL for the user to return to
			$callback = $this->request->url(NULL, TRUE);

			// Add the callback URL to the consumer
			$this->consumer->callback($callback);

			// Get a request token for the consumer
			$token = $this->provider->request_token($this->consumer);

			// Get the login URL from the provider
			$url = $this->provider->authorize_url($token);

			// Store the token
			$this->session->set($this->key('request'), $token);

			// Redirect to the twitter login page
			$this->content = HTML::anchor($url, "Login to {$this->api}");
		}
	}

	public function demo_logout()
	{
		if (Arr::get($_GET, 'confirm'))
		{
			// Delete the access token
			$this->session->delete($this->key('request'), $this->key('access'));

			// Redirect to the demo list
			$this->request->redirect($this->request->uri(array('action' => FALSE, 'id' => FALSE)));
		}

		$this->content = HTML::anchor("{$this->request->uri}?confirm=yes", "Logout of {$this->api}");
	}

} // End Demo