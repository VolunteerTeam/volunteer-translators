<?php

class OAuth_Provider_Twitter extends OAuth_Provider {

	public $name = 'twitter';
	
	public $uid_key = 'user_id';

	public function url_request_token()
	{
		return 'https://api.twitter.com/oauth/request_token';
	}

	public function url_authorize()
	{
		return 'https://api.twitter.com/oauth/authenticate';
	}

	public function url_access_token()
	{
		return 'https://api.twitter.com/oauth/access_token';
	}
	
	public function get_user_info(OAuth_Consumer $consumer, OAuth_Token $token)
	{		
		// Create a new GET request with the required parameters
		$request = OAuth_Request::forge('resource', 'GET', 'https://api.twitter.com/1.1/account/verify_credentials.json', array(
			'oauth_consumer_key' => $consumer->key,
			'oauth_token' => $token->access_token,
			'user_id' => $token->uid,
		));

		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		$user = current(json_decode($request->execute()));
		
		// Create a response from the request

        return array(
            'uid' => $token->uid,
            'username' => $user->name ? $user->name : $user->screen_name,
            'first_name' => $user->name ? $user->name : $user->screen_name,
            'last_name' =>$user->name ? $user->name : $user->screen_name,
            'provider_url'=>'http://twitter.com',
            'provider'    =>'tw',
            'screen_name'    => $user->screen_name


        );

	}

} // End Provider_Twitter