<?php defined('SYSPATH') or die('No direct script access.');

class Twitter_Direct_Message extends Twitter {

	/**
	 * @link  http://dev.twitter.com/doc/get/direct_messages
	 */
	public function received(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		// Create a new GET request with the optional parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url('direct_messages'), array(
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
	 * @link  http://dev.twitter.com/doc/get/direct_messages/sent
	 */
	public function sent(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		// Create a new GET request with the optional parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url('direct_messages/sent'), array(
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
	 * @link  http://dev.twitter.com/doc/post/direct_messages/new
	 */
	public function send(OAuth_Consumer $consumer, OAuth_Token $token, array $params = NULL)
	{
		if ( ! isset($params['user_id']) AND ! isset($params['screen_name']))
		{
			throw new Kohana_OAuth_Exception('Required parameter not passed: user_id or screen_name must be provided');
		}
		
		if ( ! isset($params['text']))
		{
			throw new Kohana_OAuth_Exception('Required parameter not passed: text');
		}

		// Create a new POST request with the required parameters
		$request = OAuth_Request::factory('resource', 'POST', $this->url('direct_messages/new'), array(
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
			throw new Kohana_OAuth_Exception('Required parameter not passed: id');
		}

		// Remove the "id" parameter, it is used in the URL
		$id = Arr::get($params, 'id');
		unset($params['id']);

		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'POST', $this->url("direct_messages/destroy/{$id}"), array(
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

} // End Twitter_Direct_Message