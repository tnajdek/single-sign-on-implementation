<?php defined('SYSPATH') or die('No direct script access.');

class Twitter_Account extends Twitter {

	/**
	 * @link  http://dev.twitter.com/doc/get/account/verify_credentials
	 */
	public function verify_credentials(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url('account/verify_credentials'), array(
			'oauth_consumer_key' => $consumer->key,
			'oauth_token'        => $token->token,
		));

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * @link  http://dev.twitter.com/doc/get/account/rate_limit_status
	 */
	public function rate_limit_status(OAuth_Consumer $consumer, OAuth_Token $token = NULL, array $params = NULL)
	{
		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url('account/rate_limit_status'), array(
			'oauth_consumer_key' => $consumer->key,
		));

		// Authorization is not required
		$request->required('oauth_token', FALSE);

		if ($token)
		{
			// Include the token
			$params['oauth_token'] = $token->token;
		}

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * @link  http://dev.twitter.com/doc/get/account/end_session
	 */
	public function end_session(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'POST', $this->url('account/end_session'), array(
			'oauth_consumer_key' => $consumer->key,
			'oauth_token'        => $token->token,
		));

		// Sending an authorization header without a POST body will cause the
		// request to fail. Instead, we will send everything as POST.
		$request->send_header = FALSE;

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	

	/**
	 * @link  http://dev.twitter.com/doc/get/account/update_delivery_device
	 */
	public function update_delivery_device(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		throw new Kohana_Exception('This endpoint has been deprecated by Twitter');
	}

	/**
	 * @link  http://dev.twitter.com/doc/get/account/update_profile_colors
	 */
	public function update_profile_colors(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'POST', $this->url('account/update_profile_colors'), array(
			'oauth_consumer_key' => $consumer->key,
			'oauth_token'        => $token->token,
		));

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * @link  http://dev.twitter.com/doc/get/account/update_profile
	 */
	public function update_profile(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'POST', $this->url('account/update_profile'), array(
			'oauth_consumer_key' => $consumer->key,
			'oauth_token'        => $token->token,
		));

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * @link  http://dev.twitter.com/doc/get/account/update_profile_image
	 */
	public function update_profile_image(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'POST', $this->url('account/update_profile_image'), array(
				'oauth_consumer_key' => $consumer->key,
				'oauth_token'        => $token->token,
			))
			->required('image', TRUE);

		// CURL options
		$options = array();

		if (isset($params['image']))
		{
			// Upload the image
			$request->upload('image', $params['image']);

			// Do not pass "image" as a normal parameter
			unset($params['image']);

			// This will probably take longer time than normal because of uploading
			$options[CURLOPT_TIMEOUT] = 60;

			$options[CURLOPT_HTTPHEADER] = array(
				// Overload the "Expect" header to bypass CURL oddity, see
				// http://code.google.com/p/twitter-api/issues/detail?id=697
				'Expect:',
			);
		}

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute($options);

		return $this->parse($response);
	}

} // End Twitter_Account
