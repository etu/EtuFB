<?php
/***
 * File: ./lib.php
 * This is the acctual lib
 * 
 ** function __construct()
 * This function is the constructor, it saves the configs to the class and fetches code and token from Sessions/GET
 *
 ** private function getAuthUrl()
 * Returns the URL you should send the user to, to authenticate and get a code. This also requests for permissions.
 *
 ** function reAuth()
 * Unsets all sessions needed for authing, getting the authUrl, kills the runtime and prints a Javascript to reauth.
 *
 ** function getAccessToken()
 * Tries to get a access token using the code, if it succseed it will return the accesstoken.
 * If it fails(usaly becouse tho code is too old) it well reauthenticate the user.
 *
 ** function getUser()
 * Just a dumb version of api(). Fetches the user from facebook, if it fails, kill and reauth.
 *
 ** function api()
 * Needs moar work to get it working with all kinds of graph api-calls.
 */

class EtuFB {
	var $app_id;
	var $secret;
	var $url;
	var $redirect;
	var $eperms;
	
	var $code;
	var $token;
	
	function __construct($fb) {
		$this->app_id   = $fb['app_id'];
		$this->secret   = $fb['secret'];
		$this->url      = $fb['url'];
		$this->redirect = $fb['redirect'];
		$this->eperms   = $fb['eperms'];
		
		if(strlen($_SESSION['code']) != 0)
			$this->code = $_SESSION['code'];	
		elseif(strlen($_GET['code']) != 0)
			$this->code = $_GET['code'];
		else
			$this->code = '';
		
		if(strlen($_SESSION['token']) != 0)
			$this->token = $_SESSION['token'];
		else
			$this->token = '';
		
		// Without this permission you will not be able to fetch accsesstoken nor do api-calls
		if(ini_get('allow_url_fopen') != 1)
			die('You have to enable <em>allow_url_fopen</em>, file_get_contents have to be able to accsess URLs to fetch the users accsess_token.');
		
		// It's allways a good idea to disable /display_errors/ in a live enviorment, debugmode will be added
		if(ini_get('display_errors') != 0)
			die('You have to disable <em>display_errors</em>, else it will not work when the accsess_token run out of time.');
	}
	
	private function getAuthUrl() {
		$auth_url = 'https://graph.facebook.com/oauth/authorize'.
			'?client_id='.$this->app_id.
			'&redirect_uri='.$this->redirect.
			'&scope='.$this->eperms;
		return $auth_url;
	}

	function reAuth() {
		unset($_SESSION['code']);
		unset($_SESSION['token']);
		die('<script>window.top.location="'.$this->getAuthUrl().'";</script>');
	}
	
	function getAccessToken() {
		$access_token_url = 'https://graph.facebook.com/oauth/access_token'.
			'?client_id='.$this->app_id.
			'&redirect_uri='.$this->redirect.
			'&client_secret='.$this->secret.
			'&code='.$this->code;
		
		$result = file_get_contents($access_token_url);
		
		// If the fetching of an access token fails... ReAuth the user!
		if($result === false) {
			$this->reAuth();
		} else {
			$this->token       = $result;
			$_SESSION['code']  = $this->code;
			$_SESSION['token'] = $this->token;
			
			return $result;
		}
	}
	
	function getUser() {
		$result = file_get_contents('https://graph.facebook.com/me?'.$this->token);
		
		if($result === false)
			$this->reAuth();	
		else
			return $result;
	}
	
	function api($call) {
		$url = 'https://graph.facebook.com/'.$call.'?'.$this->token;
		$result = file_get_contents($url);
		return json_decode($result);
	}
}