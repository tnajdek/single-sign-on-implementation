<?php defined('SYSPATH') or die('No direct script access.');

class Blogger_Posts extends Blogger {

	/**
	 * Create a post.
	 *
	 *		Blogger::factory('posts')->create($consumer, $token, $blog_id, $content);
	 *
	 * @param   OAuth_Consumer	consumer
	 * @param   OAuth_Token		token
	 * @param   integer			blog ID
	 * @param   string			the content of the post, format: http://code.google.com/apis/blogger/docs/2.0/developers_guide_protocol.html#CreatingEntries
	 * @return  mixed
	 */
	public function create(OAuth_Consumer $consumer, OAuth_Token $token, $blog_id, $content)
	{
		// Create a new POST request with the required parameters
		$request = OAuth_Request::factory('resource', 'POST', $this->url($blog_id, 'posts/default'), array(
				'oauth_consumer_key' => $consumer->key,
				'oauth_token' => $token->token,
			));

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute(array(
			CURLOPT_HTTPHEADER => array(
				"Content-Type: {$this->format}",
				"GData-Version: {$this->version}",
			),
			CURLOPT_POSTFIELDS => $content,
		));

		return $this->parse($response);
	}

	/**
	 * Retrieve posts.
	 *
	 *		Blogger::factory('posts')->read($consumer, $token, $blog_id, NULL, array('orderby' => 'updated'));
	 *
	 * IMPORTANT: define categories in parameters like this: array('categories' => '/Fritz/Laurie')
	 *
	 * @param   OAuth_Consumer	consumer
	 * @param   OAuth_Token		token
	 * @param   string			blog ID
	 * @param   string			post ID for a single post, if set to NULL all posts are retrieved
	 * @param   array			optional query parameters for search: http://code.google.com/apis/blogger/docs/2.0/developers_guide_protocol.html#RetrievingWithQuery
	 * @return  mixed
	 */
	public function read(OAuth_Consumer $consumer, OAuth_Token $token, $blog_id, $post_id = NULL, array $params = NULL)
	{
		// Look for categories in params
		if ($categories = Arr::get($params, 'categories'))
		{
			// Set post_id to '-' if categories are found
			$post_id = '-';
		}

		// Categories must not in query parameters
		unset($params['categories']);

		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url($blog_id, "posts/default/{$post_id}{$categories}"), array(
				'oauth_consumer_key' => $consumer->key,
				'oauth_token' => $token->token,
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
	 * Update a post.
	 *
	 *		Blogger::factory('posts')->update($consumer, $token, $blog_id, $post_id);
	 *
	 * @param   OAuth_Consumer	consumer
	 * @param   OAuth_Token		token
	 * @param   string			blog ID
	 * @param   string			post ID
	 * @param   string			the content of the post, format: http://code.google.com/apis/blogger/docs/2.0/developers_guide_protocol.html#CreatingEntries
	 * @return  mixed
	 */
	public function update(OAuth_Consumer $consumer, OAuth_Token $token, $blog_id, $post_id, $content)
	{
		// Create a new POST request with the required parameters
		// Some firewalls do not allow PUT, so POST is used and X-HTTP-Method-Override: PUT is set in headers
		$request = OAuth_Request::factory('resource', 'POST', $this->url($blog_id, "posts/default/{$post_id}"), array(
				'oauth_consumer_key' => $consumer->key,
				'oauth_token' => $token->token,
			));

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute(array(
			CURLOPT_HTTPHEADER => array(
				"Content-Type: {$this->format}",
				"GData-Version: {$this->version}",
				'X-HTTP-Method-Override: PUT',
			),
			CURLOPT_POSTFIELDS => $content,
		));

		return $this->parse($response);
	}

	/**
	 * Delete a post.
	 *
	 *		Blogger::factory('posts')->delete($consumer, $token, $blog_id, $post_id);
	 *
	 * @param   OAuth_Consumer	consumer
	 * @param   OAuth_Token		token
	 * @param   string			blog ID
	 * @param   string			post ID
	 * @return  mixed
	 */
	public function delete(OAuth_Consumer $consumer, OAuth_Token $token, $blog_id, $post_id)
	{
		// Create a new POST request with the required parameters
		// Some firewalls do not allow DELETE, so POST is used and X-HTTP-Method-Override: DELETE is set in headers
		$request = OAuth_Request::factory('resource', 'POST', $this->url($blog_id, "posts/default/{$post_id}"), array(
				'oauth_consumer_key' => $consumer->key,
				'oauth_token' => $token->token,
			));

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute(array(
			CURLOPT_HTTPHEADER => array(
				"Content-Type: {$this->format}",
				"GData-Version: {$this->version}",
				'X-HTTP-Method-Override: DELETE',
			),
		));

		return $this->parse($response);
	}

} // End Blogger_Posts