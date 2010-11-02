<?php
/***
 * File: ./lib.php
 * This is the acctual lib
 *
 ** function getAuthUrl()
 * Returns the URL you should send the user to, to authenticate and get a code.
 *
 ** function getAccsessToken()
 * Tries to get a accsess token using the code, if it succseed it will return the accsesstoken.
 * If it fails(usaly becouse tho code is too old) it well reouthenticate the user.
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
	
	function __construct($fb) {
		$this->app_id   = $fb['app_id'];
		$this->secret   = $fb['secret'];
		$this->url      = $fb['url'];
		$this->redirect = $fb['redirect'];
		$this->eperms   = $fb['eperms'];
		
		// Without this permission you will not be able to fetch accsesstoken nor do api-calls
		if(ini_get('allow_url_fopen') != 1)
			die('You have to enable <em>allow_url_fopen</em>, file_get_contents have to be able to accsess URLs to fetch the users accsess_token.');
		
		// It's allways a good idea to disable /display_errors/ in a live enviorment, debugmode will be added
		if(ini_get('display_errors') != 0)
			die('You have to disable <em>display_errors</em>, else it will not work when the accsess_token run out of time.');
	}
	
	function getAuthUrl() {
		$auth_url = 'https://graph.facebook.com/oauth/authorize'.
			'?client_id='.$this->app_id.
			'&redirect_uri='.$this->redirect.
			'&scope='.$this->eperms;
		return $auth_url;
	}
	
	function getAccessToken($code) {
		$access_token_url = 'https://graph.facebook.com/oauth/access_token'.
			'?client_id='.$this->app_id.
			'&redirect_uri='.$this->redirect.
			'&client_secret='.$this->secret.
			'&code='.$code;
		
		$result = file_get_contents($access_token_url);
		
		// If the fetching of an access token fails... Reauth the user.
		if($result === false) {
			die('<script>window.top.location="'.$this->redirect.'";</script>');
		} else {
			return $result;
		}
	}
	
	function api($call, $token = '') {
		$api_get_user_url = 'https://graph.facebook.com/'.$call.'?'.$token;
		$user = file_get_contents($api_get_user_url);
		return json_decode($user);
	}
}
