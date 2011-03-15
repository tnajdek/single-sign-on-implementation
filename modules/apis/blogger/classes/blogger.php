<?php defined('SYSPATH') or die('No direct script access.');

abstract class Blogger extends OAuth_Provider_Google {

	protected $version = '2';

	protected $format = 'application/atom+xml';

	protected $parser = array(
		'application/atom+xml'  => 'simplexml_load_string',
		'application/json' => 'json_decode',
	);

	public static function factory($name, array $options = NULL)
	{
		$class = 'Blogger_'.$name;

		return new $class($options);
	}

	public function __construct(array $options = NULL)
	{
		parent::__construct($options);

		if (isset($options['version']))
		{
			// Set the Blogger API version
			$this->version = (int) $options['version'];
		}

		if (isset($options['format']))
		{
			// Set the response format
			$this->format = trim($options['format']);
		}
	}

	public function parser($format, $value)
	{
		$this->parser[$format] = $value;

		return $this;
	}

	public function url($resource_id, $action)
	{
		return "http://www.blogger.com/feeds/{$resource_id}/{$action}";
	}

	public function parse($response)
	{
		if ( ! isset($this->parser[$this->format]))
		{
			// No parser for the requested format
			return $response;
		}

		// Get the parser for this format
		$parser = $this->parser[$this->format];

		// Parse the response
		return $parser($response);
	}

} // End Blogger