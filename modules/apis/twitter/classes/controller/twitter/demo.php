<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Twitter_Demo extends Controller_Demo {

	public function demo_account_verify_credentials()
	{
		$api = Twitter::factory('account');

		$response = $api->verify_credentials($this->consumer, $this->token);

		$this->content = Kohana::debug($response);
	}

	public function demo_account_rate_limit_status()
	{
		$api = Twitter::factory('account');

		$response = $api->rate_limit_status($this->consumer, $this->token);

		$this->content = Kohana::debug($response);
	}

	public function demo_account_end_session()
	{
		$api = Twitter::factory('account');

		$response = $api->end_session($this->consumer, $this->token);

		// The access token is not valid after ending the session
		$this->session->delete($this->key('access'));

		$this->content = Kohana::debug($response);
	}

	public function demo_account_update_profile_colors()
	{
		if (Request::$method === 'POST')
		{
			// Get the screen name and account id from POST
			$params = Arr::extract($_POST, array(
				'profile_background_color',
				'profile_text_color',
				'profile_link_color',
				'profile_sidebar_fill_color',
				'profile_sidebar_border_color',
			));

			if ( ! $params)
			{
				// No parameters included
				$this->request->redirect($this->request->uri);
			}

			foreach ($params as $key => $value)
			{
				if ( ! ltrim($value, '#'))
				{
					// Remove empty value
					unset($params[$key]);
				}
			}

			$api = Twitter::factory('account');

			$response = $api->update_profile_colors($this->consumer, $this->token, $params);

			$this->content = Kohana::debug($response);
		}
		else
		{
			$this->content = View::factory('api/form')
				->set('message', 'Choose your profile colors using 3 or 6 digit hex codes. <small class="warn">Do not include the # sign!</small>')
				->set('inputs', array(
					'Background'     => Form::input('profile_background_color'),
					'Text'           => Form::input('profile_text_color'),
					'Links'          => Form::input('profile_link_color'),
					'Sidebar Fill'   => Form::input('profile_sidebar_fill_color'),
					'Sidebar Border' => Form::input('profile_sidebar_border_color'),
				))
				;
		}
	}

	public function demo_account_update_profile()
	{
		if (Request::$method === 'POST')
		{
			// Get the screen name and account id from POST
			$params = Arr::extract($_POST, array(
				'name',
				'url',
				'location',
				'description',
			));

			if ( ! $params)
			{
				// No parameters included
				$this->request->redirect($this->request->uri);
			}

			foreach ($params as $key => $value)
			{
				if ( ! trim($value))
				{
					// Remove empty value
					unset($params[$key]);
				}
			}

			$api = Twitter::factory('account');

			$response = $api->update_profile($this->consumer, $this->token, $params);

			$this->content = Kohana::debug($response);
		}
		else
		{
			$this->content = View::factory('api/form')
				->set('message', 'Update your profile.')
				->set('inputs', array(
					'Your Name'   => Form::input('name'),
					'Website URL' => Form::input('url'),
					'Location'    => Form::input('location'),
					'Description' => Form::input('description'),
				))
				;
		}
	}

	public function demo_account_update_profile_image()
	{
		if (Request::$method === 'POST')
		{
			$params = array('image' => Upload::save($_FILES['image'], NULL, '/tmp'));

			$api = Twitter::factory('account');

			$response = $api->update_profile_image($this->consumer, $this->token, $params);

			$this->content = Kohana::debug($response);
		}
		else
		{
			$this->content = View::factory('api/form')
				->set('uploads', TRUE) // ALlow uploads
				->set('message', 'Update your profile image.')
				->set('inputs', array(
					'Image File' => Form::file('image'),
				))
				;
		}
	}

	public function demo_status_friends_timeline()
	{
		$api = Twitter::factory('status');

		$response = $api->friends_timeline($this->consumer, $this->token);

		$this->content = Kohana::debug($response);
	}

	public function demo_status_home_timeline()
	{
		$api = Twitter::factory('status');

		$response = $api->home_timeline($this->consumer, $this->token);

		$this->content = Kohana::debug($response);
	}

	public function demo_status_mentions()
	{
		$api = Twitter::factory('status');

		$response = $api->mentions($this->consumer, $this->token);

		$this->content = Kohana::debug($response);
	}

	public function demo_status_retweeted_by_me()
	{
		$api = Twitter::factory('status');

		$response = $api->retweeted_by_me($this->consumer, $this->token);

		$this->content = Kohana::debug($response);
	}

	public function demo_status_retweeted_to_me()
	{
		$api = Twitter::factory('status');

		$response = $api->retweeted_to_me($this->consumer, $this->token);

		$this->content = Kohana::debug($response);
	}

	public function demo_status_retweets_of_me()
	{
		$api = Twitter::factory('status');

		$response = $api->retweets_of_me($this->consumer, $this->token);

		$this->content = Kohana::debug($response);
	}

	public function demo_status_public_timeline()
	{
		$api = Twitter::factory('status');

		$response = $api->public_timeline($this->consumer);

		$this->content = Kohana::debug($response);
	}

	public function demo_status_user_timeline()
	{
		if (Request::$method === 'POST')
		{
			// Get the screen name and account id from POST
			$params = Arr::extract($_POST, array('screen_name', 'account_id'));

			if ( ! $params)
			{
				// No parameters included
				$this->request->redirect($this->request->uri);
			}

			$api = Twitter::factory('status');

			$response = $api->user_timeline($this->consumer, $this->token, $params);

			$this->content = Kohana::debug($response);
		}
		else
		{
			$this->content = View::factory('api/form')
				->set('message', 'Enter an account ID or screen name.')
				->set('inputs', array(
					'Screen Name' => Form::input('screen_name'),
					'Account ID'  => Form::input('acount_id'),
				))
				;
		}
	}

	public function demo_status_show()
	{
		if (Request::$method === 'POST')
		{
			// Get the screen name and account id from POST
			$params = Arr::extract($_POST, array('id'));

			if ( ! $params)
			{
				// No parameters included
				$this->request->redirect($this->request->uri);
			}

			$api = Twitter::factory('status');

			$response = $api->show($this->consumer, $this->token, $params);

			$this->content = Kohana::debug($response);
		}
		else
		{
			$this->content = View::factory('api/form')
				->set('message', 'Enter a tweet (status) id.')
				->set('inputs', array(
					'ID' => Form::input('id'),
				))
				;
		}
	}

	public function demo_status_update()
	{
		if (Request::$method === 'POST')
		{
			$params = Arr::extract($_POST, array('status'));

			if ( ! $params)
			{
				// No parameters included
				$this->request->redirect($this->request->uri);
			}

			$api = Twitter::factory('status');

			$response = $api->update($this->consumer, $this->token, $params);

			$this->content = Kohana::debug($response);
		}
		else
		{
			$this->content = View::factory('api/form')
				->set('message', 'Enter your tweet text.')
				->set('inputs', array(
					'Status' => Form::textarea('status'),
				))
				;
		}
	}

	public function demo_status_destroy()
	{
		if (Request::$method === 'POST')
		{
			// Get the screen name and account id from POST
			$params = Arr::extract($_POST, array('id'));

			if ( ! $params)
			{
				// No parameters included
				$this->request->redirect($this->request->uri);
			}

			$api = Twitter::factory('status');

			$response = $api->destroy($this->consumer, $this->token, $params);

			$this->content = Kohana::debug($response);
		}
		else
		{
			$this->content = View::factory('api/form')
				->set('message', 'Enter a tweet (status) id. You must own the tweet you want to destroy.')
				->set('inputs', array(
					'ID' => Form::input('id'),
				))
				;
		}
	}

	public function demo_user_show()
	{
		if (Request::$method === 'POST')
		{
			// Get the screen name and account id from POST
			$params = Arr::extract($_POST, array('screen_name', 'account_id'));

			if ( ! $params)
			{
				// No parameters included
				$this->request->redirect($this->request->uri);
			}

			$api = Twitter::factory('user');

			$response = $api->show($this->consumer, $this->token, $params);

			$this->content = Kohana::debug($response);
		}
		else
		{
			$this->content = View::factory('api/form')
				->set('message', 'Enter an account ID or screen name.')
				->set('inputs', array(
					'Screen Name' => Form::input('screen_name'),
					'Account ID'  => Form::input('acount_id'),
				))
				;
		}
	}

	public function demo_user_lookup()
	{
		if (Request::$method === 'POST')
		{
			// Get the screen name and account id from POST
			$params = Arr::extract($_POST, array('screen_name', 'account_id'));

			if ( ! $params)
			{
				// No parameters included
				$this->request->redirect($this->request->uri);
			}

			$api = Twitter::factory('user');

			$response = $api->lookup($this->consumer, $this->token, $params);

			$this->content = Kohana::debug($response);
		}
		else
		{
			$this->content = View::factory('api/form')
				->set('message', 'Enter a comma-separated list of account IDs or screen names.')
				->set('inputs', array(
					'Screen Names' => Form::textarea('screen_name'),
					'Account IDs'  => Form::textarea('acount_id'),
				))
				;
		}
	}

	public function demo_user_search()
	{
		if (Request::$method === 'POST')
		{
			// Get the query from POST
			$params = Arr::extract($_POST, array('q'));

			if ( ! $params)
			{
				// No parameters included
				$this->request->redirect($this->request->uri);
			}

			$api = Twitter::factory('user');

			$response = $api->search($this->consumer, $this->token, $params);

			$this->content = Kohana::debug($response);
		}
		else
		{
			$this->content = View::factory('api/form')
				->set('message', 'Enter search terms.')
				->set('inputs', array(
					'Search' => Form::input('q'),
				))
				;
		}
	}

	public function demo_user_suggestions()
	{
		if (Request::$method === 'POST')
		{
			$params = Arr::extract($_POST, array('slug'));

			if ( ! $params)
			{
				// No parameters included
				$this->request->redirect($this->request->uri);
			}

			$api = Twitter::factory('user');

			// Get a list of user suggestions in this category
			$response = $api->suggestions($this->consumer, $this->token, $params);

			$this->content = Kohana::debug($response);
		}
		else
		{
			$api = Twitter::factory('user');

			// Without the "slug" parameter, a list of categories is returned
			$categories = $api->suggestions($this->consumer, $this->token);

			$options = array();
			foreach ($categories as $category)
			{
				$options[$category->slug] = $category->name;
			}

			$this->content = View::factory('api/form')
				->set('message', 'Choose a category.')
				->set('inputs', array(
					'Category' => Form::select('slug', $options),
				))
				;
		}
	}

	public function demo_user_profile_image()
	{
		if (Request::$method === 'POST')
		{
			$params = Arr::extract($_POST, array('screen_name'));

			if ( ! $params)
			{
				// No parameters included
				$this->request->redirect($this->request->uri);
			}

			$api = Twitter::factory('user');

			$response = $api->profile_image($this->consumer, $this->token, $params);

			$this->content = Kohana::debug($response);
		}
		else
		{
			$this->content = View::factory('api/form')
				->set('message', 'Enter a screen name. <small class="warn">This method will always result in an exception because Twitter returns a 302 redirect rather than a response!</small>')
				->set('inputs', array(
					'Screen Name' => Form::input('screen_name'),
				))
				;
		}
	}

	public function demo_user_friends()
	{
		if (Request::$method === 'POST')
		{
			// Get the screen name and account id from POST
			$params = Arr::extract($_POST, array('screen_name', 'account_id'));

			if ( ! $params)
			{
				// No parameters included
				$this->request->redirect($this->request->uri);
			}

			$api = Twitter::factory('user');

			$response = $api->friends($this->consumer, $this->token, $params);

			$this->content = Kohana::debug($response);
		}
		else
		{
			$this->content = View::factory('api/form')
				->set('message', 'Enter an account ID or screen name.')
				->set('inputs', array(
					'Screen Name' => Form::input('screen_name'),
					'Account ID'  => Form::input('acount_id'),
				))
				;
		}
	}

	public function demo_user_followers()
	{
		if (Request::$method === 'POST')
		{
			// Get the screen name and account id from POST
			$params = Arr::extract($_POST, array('screen_name', 'account_id'));

			if ( ! $params)
			{
				// No parameters included
				$this->request->redirect($this->request->uri);
			}

			$api = Twitter::factory('user');

			$response = $api->followers($this->consumer, $this->token, $params);

			$this->content = Kohana::debug($response);
		}
		else
		{
			$this->content = View::factory('api/form')
				->set('message', 'Enter an account ID or screen name.')
				->set('inputs', array(
					'Screen Name' => Form::input('screen_name'),
					'Account ID'  => Form::input('acount_id'),
				))
				;
		}
	}

} // End Twitter_Demo