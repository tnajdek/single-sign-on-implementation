<?php defined('SYSPATH') or die('No direct script access.');

class Twitter_Status extends Twitter {

	/**
	 * @link  http://dev.twitter.com/doc/get/statuses/public_timeline
	 */
	public function public_timeline(OAuth_Consumer $consumer, OAuth_Token $token = NULL, array $params = NULL)
	{
		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url('statuses/public_timeline'), array(
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

		// Sign the request using only the consumer, no token is required
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * @link  http://dev.twitter.com/doc/get/statuses/home_timeline
	 */
	public function home_timeline(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url('statuses/home_timeline'), array(
				'oauth_consumer_key' => $consumer->key,
				'oauth_token'        => $token->token,
			));

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		// Sign the request using only the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * @link  http://dev.twitter.com/doc/get/statuses/friends_timeline
	 */
	public function friends_timeline(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url('statuses/friends_timeline'), array(
				'oauth_consumer_key' => $consumer->key,
				'oauth_token'        => $token->token,
			));

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		// Sign the request using only the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * @link  http://dev.twitter.com/doc/get/statuses/user_timeline
	 */
	public function user_timeline(OAuth_Consumer $consumer, OAuth_Token $token = NULL, array $params = NULL)
	{
		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url('statuses/user_timeline'), array(
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

		// Sign the request using only the consumer, token is not required
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * @link  http://dev.twitter.com/doc/get/statuses/mentions
	 */
	public function mentions(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url('statuses/mentions'), array(
				'oauth_consumer_key' => $consumer->key,
				'oauth_token'        => $token->token,
			));

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		// Sign the request using only the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * @link  http://dev.twitter.com/doc/get/statuses/retweeted_by_me
	 */
	public function retweeted_by_me(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url('statuses/retweeted_by_me'), array(
				'oauth_consumer_key' => $consumer->key,
				'oauth_token'        => $token->token,
			));

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		// Sign the request using only the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * @link  http://dev.twitter.com/doc/get/statuses/retweeted_to_me
	 */
	public function retweeted_to_me(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url('statuses/retweeted_to_me'), array(
				'oauth_consumer_key' => $consumer->key,
				'oauth_token'        => $token->token,
			));

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		// Sign the request using only the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * @link  http://dev.twitter.com/doc/get/statuses/retweets_of_me
	 */
	public function retweets_of_me(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url('statuses/retweets_of_me'), array(
				'oauth_consumer_key' => $consumer->key,
				'oauth_token'        => $token->token,
			));

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		// Sign the request using only the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * @link  http://dev.twitter.com/doc/get/statuses/friends
	 */
	public function friends(OAuth_Consumer $consumer, OAuth_Token $token = NULL, array $params = NULL)
	{
		if ( ! isset($params['user_id']) AND ! isset($params['screen_name']))
		{
			throw new Kohana_OAuth_Exception('Required parameter not passed: user_id or screen_name must be provided');
		}

		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url('statuses/friends'), array(
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
	 * @link  http://dev.twitter.com/doc/get/statuses/followers
	 */
	public function followers(OAuth_Consumer $consumer, OAuth_Token $token = NULL, array $params = NULL)
	{
		if ( ! isset($params['user_id']) AND ! isset($params['screen_name']))
		{
			throw new Kohana_OAuth_Exception('Required parameter not passed: user_id or screen_name must be provided');
		}

		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url('statuses/followers'), array(
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
	 * @link  http://dev.twitter.com/doc/get/statuses/show/:id
	 */
	public function show(OAuth_Consumer $consumer, OAuth_Token $token = NULL, array $params = NULL)
	{
		if ( ! isset($params['id']))
		{
			throw new Kohana_OAuth_Exception('Required parameter not passed: :param', array(
					':param' => 'id',
				));
		}

		// Remove the "id" parameter, it is used in the URL
		$id = Arr::get($params, 'id');

		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url("statuses/show/{$id}"), array(
				'oauth_consumer_key' => $consumer->key,
			))
			->required('oauth_token', FALSE)
			;

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
	 * @link  http://dev.twitter.com/doc/get/statuses/update
	 */
	public function update(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		if ( ! isset($params['status']))
		{
			throw new Kohana_OAuth_Exception('Required parameter not passed: :param', array(
				':param' => 'status',
			));
		}

		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'POST', $this->url('statuses/update'), array(
				'oauth_consumer_key' => $consumer->key,
				'oauth_token'        => $token->token,
			));

		if ($params)
		{
			// Load user parameters
			$request->params($params);
		}

		// Sign the request using only the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * @link  http://dev.twitter.com/doc/get/statuses/destroy/:id
	 */
	public function destroy(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		if ( ! isset($params['id']))
		{
			throw new Kohana_OAuth_Exception('Required parameter not passed: :param', array(
					':param' => 'id',
				));
		}

		// Remove the "id" parameter, it is used in the URL
		$id = Arr::get($params, 'id');

		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'POST', $this->url("statuses/destroy/{$id}"), array(
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

} // End Twitter_Status