<?php defined('SYSPATH') or die('No direct script access.');

class Blogger_User extends Blogger {

	/**
	 * Retrieve list of blogs.
	 *
	 * 		Blogger::factory('user')->blogs($consumer, $token, $profile_id);
	 *
	 * @param   OAuth_Consumer	consumer
	 * @param   OAuth_Token		token
	 * @param   string			blog ID
	 * @param   string			profile ID, if set to 'default' the currently authenticated user's profile ID is used
	 * @return  mixed
	 */
	public function blogs(OAuth_Consumer $consumer, OAuth_Token $token, $profile_id = 'default')
	{
		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url($profile_id, 'blogs'), array(
				'oauth_consumer_key' => $consumer->key,
				'oauth_token' => $token->token,
			));

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

} // End Blogger_User