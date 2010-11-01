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
	var $app_secret;
	var $redirect_url;
	var $extended_permissions;
	
	function __construct($app_id, $secret, $redirect_url, $extpermissions = '') {
		$this->app_id = $app_id;
		$this->app_secret = $secret;
		$this->redirect_url = $redirect_url;
		$this->extended_permissions = $extpermissions;
		
		if(ini_get('allow_url_fopen') != 1) // Without this permission you will not be able to fetch accsesstoken nor do api-calls
			die('You have to enable <em>allow_url_fopen</em>, file_get_contents have to be able to accsess URLs to fetch the users accsess_token.');
		if(ini_get('display_errors') != 0)  // It's allways a good idea to disable /display_errors/ in a live enviorment, debugmode will be added
			die('You have to disable <em>display_errors</em>, else it will not work when the accsess_token run out of time.');
	}
	
	function getAuthUrl() {
		$auth_url = 'https://graph.facebook.com/oauth/authorize'.
			'?client_id='.$this->app_id.
			'&redirect_uri='.$this->redirect_url.
			'&scope='.$this->extended_permissions;
		return $auth_url;
	}
	
	function getAccsessToken($code) {
		$accsess_token_url = 'https://graph.facebook.com/oauth/access_token'.
			'?client_id='.$this->app_id.
			'&redirect_uri='.$this->redirect_url.
			'&client_secret='.$this->app_secret.
			'&code='.$code;
		
		$result = file_get_contents($accsess_token_url);
		if($result === false) {
			die('<script>window.top.location="'.$this->redirect_url.'";</script>'); // Reauthing to get new code
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
