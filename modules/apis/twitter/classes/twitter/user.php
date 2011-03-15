<?php defined('SYSPATH') or die('No direct script access.');

class Twitter_User extends Twitter {

	/**
	 * @link  http://dev.twitter.com/doc/get/users/show
	 */
	public function show(OAuth_Consumer $consumer, OAuth_Token $token = NULL, array $params = NULL)
	{
		if ( ! isset($params['user_id']) AND ! isset($params['screen_name']))
		{
			throw new Kohana_OAuth_Exception('Required parameter not passed: user_id or screen_name must be provided');
		}

		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url('users/show'), array(
				'oauth_consumer_key' => $consumer->key,
			))
			->required('oauth_token', FALSE);

		if ($token)
		{
			// Include the access token
			$params['oauth_token'] = $token->token;
		}

		// Load user parameters
		$request->params($params);

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * @link  http://dev.twitter.com/doc/get/users/lookup
	 */
	public function lookup(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		if ( ! isset($params['user_id']) AND ! isset($params['screen_name']))
		{
			throw new Kohana_OAuth_Exception('Required parameter not passed: user_id or screen_name must be provided');
		}

		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url('users/lookup'), array(
				'oauth_consumer_key' => $consumer->key,
				'oauth_token'        => $token->token,
			));

		// Load user parameters
		$request->params($params);

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * @link  http://dev.twitter.com/doc/get/users/lookup
	 */
	public function search(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		if ( ! isset($params['q']))
		{
			throw new Kohana_OAuth_Exception('Required parameter not passed: :param', array(
				':param' => 'q',
			));
		}

		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url('users/search'), array(
				'oauth_consumer_key' => $consumer->key,
				'oauth_token'        => $token->token,
			));

		// Load user parameters
		$request->params($params);

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * @link  http://dev.twitter.com/doc/get/users/suggestions
	 */
	public function suggestions(OAuth_Consumer $consumer, OAuth_Token $token = NULL, array $params = NULL)
	{
		// Get the "slug" parameter, it is used in the URL (optional)
		$slug = Arr::get($params, 'slug');

		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url("users/suggestions/{$slug}"), array(
				'oauth_consumer_key' => $consumer->key,
			))
			->required('oauth_token', FALSE);

		if ($token)
		{
			// Include the access token
			$params['oauth_token'] = $token->token;
		}

		// Load user parameters
		$request->params($params);

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * @link  http://dev.twitter.com/doc/get/users/profile_image
	 */
	public function profile_image(OAuth_Consumer $consumer, OAuth_Token $token = NULL, array $params = NULL)
	{
		if ( ! isset($params['screen_name']))
		{
			throw new Kohana_OAuth_Exception('Required parameter not passed: :param', array(
				':param' => 'screen_name',
			));
		}

		// Get the "screen_name" parameter, it is used in the URL
		$screen_name = Arr::get($params, 'screen_name');

		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url("users/profile_image/{$screen_name}"), array(
				'oauth_consumer_key' => $consumer->key,
			))
			->required('oauth_token', FALSE);

		if ($token)
		{
			// Include the access token
			$params['oauth_token'] = $token->token;
		}

		// Load user parameters
		$request->params($params);

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * @uses  Twitter_Status::followers
	 */
	public function followers(OAuth_Consumer $consumer, OAuth_Token $token = NULL, array $params = NULL)
	{
		return Twitter::factory('status')->followers($consumer, $token, $params);
	}

	/**
	 * @uses  Twitter_Status::friends
	 */
	public function friends(OAuth_Consumer $consumer, OAuth_Token $token = NULL, array $params = NULL)
	{
		return Twitter::factory('status')->friends($consumer, $token, $params);
	}

} // End Twitter_User