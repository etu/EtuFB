<?php
/***
 * File: ./init.php
 * This file is an example of how you initializes the lib, auths the user, get access_token, and fetches the user.
 */

session_start();

require_once('../config.php');
require_once('../lib.php');

$facebook = new EtuFB($fb);

// Retrive the code from the session from the sessioncookie (This will not happen with IE)
if(strlen($_SESSION['code']) != 0)
	$facebook->code = $_SESSION['code'];	
elseif(strlen($_GET['code']) != 0)
	$facebook->code = $_GET['code'];
else
	$facebook->code = '';

// If the code is _not_ set, the program will break and redirect the user to the authpage
if (strlen($facebook->code) == 0)
	die('<script>window.top.location = "'.$facebook->getAuthUrl().'";</script>');
else {
	// And... When the redirection is done, and the user is authed. The prossedure continues to get a access_token
	// If it fails to gets access_token, becouse the code is old(or some other reason). It will die and reauth the user.
	$token = $facebook->getAccessToken($facebook->code);
	
	$_SESSION['code'] = $facebook->code;
	$facebook->token = $token;
}

// Use the graph API to get the logged is user from facebook :)
$user = $facebook->api('me', $facebook->token);
