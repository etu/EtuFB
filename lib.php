<?php

class EtuFB {
	var $app_id;
	var $app_secret;
	var $redirect_url;
	var $extended_permissions;
	
	var $error_reporting;
	
	function __construct($app_id, $secret, $redirect_url, $extpermissions = '') {
		$this->app_id = $app_id;
		$this->app_secret = $secret;
		$this->redirect_url = $redirect_url;
		$this->extended_permissions = $extpermissions;
		
		if(ini_get('allow_url_fopen') != 1)
			die('You have to enable <em>allow_url_fopen</em>, file_get_contents have to be able to accsess URLs to fetch the users accsess_token.');
		if(ini_get('display_errors') != 0)
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
