<?php defined('SYSPATH') or die('No direct script access.');

abstract class Twitter extends OAuth_Provider_Twitter {

	protected $version = '1';

	protected $format = 'json';

	protected $base_url;

	protected $parser = array(
		'json' => 'json_decode',
		'xml'  => 'simplexml_load_string',
	);

	public static function factory($name, array $options = NULL)
	{
		$class = 'Twitter_'.$name;

		return new $class($options);
	}

	public function __construct(array $options = NULL)
	{
		parent::__construct($options);

		if (isset($options['version']))
		{
			// Set the Twitter API version
			$this->version = (int) $options['version'];
		}

		if (isset($options['format']))
		{
			$this->format = trim($options['format'], '.');
		}
	}

	public function parser($format, $value)
	{
		$this->parser[$format] = $value;

		return $this;
	}

	public function url($action)
	{
		// Clean up the action
		$action = trim($action, '/');

		return "http://api.twitter.com/{$this->version}/{$action}.{$this->format}";
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

} // End Twitter
