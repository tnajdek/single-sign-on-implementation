<?php defined('SYSPATH') or die('No direct script access.');

class Blogger_Comments extends Blogger {

	/**
	 * Create a comment.
	 *
	 *		Blogger::factory('comments')->create($consumer, $token, $blog_id, $post_id, $content);
	 *
	 * @param   OAuth_Consumer	consumer
	 * @param   OAuth_Token		token
	 * @param   string			blog ID
	 * @param   string			the content of the comment, format: http://code.google.com/apis/blogger/docs/2.0/developers_guide_protocol.html#CreatingComments
	 * @return  mixed
	 */
	public function create(OAuth_Consumer $consumer, OAuth_Token $token, $blog_id, $post_id, $content)
	{
		// Create a new POST request with the required parameters
		$request = OAuth_Request::factory('resource', 'POST', $this->url($blog_id, "{$post_id}/comments/default"), array(
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
	 * Retrieve comments.
	 *
	 *		Blogger::factory('comments')->read($consumer, $token, $blog_id, $post_id);
	 *
	 * @param   OAuth_Consumer	consumer
	 * @param   OAuth_Token		token
	 * @param   string			blog ID
	 * @param   string			post ID for a single post's comments, if set to NULL comments for all posts are retrieved
	 * @return  mixed
	 */
	public function read(OAuth_Consumer $consumer, OAuth_Token $token, $blog_id, $post_id = NULL)
	{
		// If post_id is set add a forward slash after it
		if ($post_id !== NULL)
		{
			$post_id = $post_id.'/';
		}

		// Create a new GET request with the required parameters
		$request = OAuth_Request::factory('resource', 'GET', $this->url($blog_id, "{$post_id}comments/default"), array(
				'oauth_consumer_key' => $consumer->key,
				'oauth_token' => $token->token,
			));

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		// Create a response from the request
		$response = $request->execute();

		return $this->parse($response);
	}

	/**
	 * Delete a comment.
	 *
	 *		Blogger::factory('comments')->delete($consumer, $token, $blog_id, $post_id, $comment_id);
	 *
	 * @param   OAuth_Consumer	consumer
	 * @param   OAuth_Token		token
	 * @param   string			blog ID
	 * @param   string			post ID
	 * @param   string			comment ID
	 * @return  mixed
	 */
	public function delete(OAuth_Consumer $consumer, OAuth_Token $token, $blog_id, $post_id, $comment_id)
	{
		// Create a new POST request with the required parameters
		// Some firewalls do not allow DELETE, so POST is used and X-HTTP-Method-Override: DELETE is set in headers
		$request = OAuth_Request::factory('resource', 'POST', $this->url($blog_id, "{$post_id}/comments/default/{$comment_id}"), array(
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

} // End Blogger_Comments