<?php
/***
 * File: ./init.php
 * This file is an example of how you initializes the lib, auths the user, get access_token, and fetches the user.
 */

session_start();

// Internet Explorer Emulation
//unset($_SESSION['code']);
//unset($_SESSION['token']);

require_once('../config.php');
require_once('../lib.php');

// Create the object, and send in the configruation variable
$facebook = new EtuFB($fb);

// If the user don't have a code, reauth.
if (strlen($facebook->code) == 0)
	$facebook->reAuth();

// If the user don't have a token, get a token.
if (strlen($facebook->token) == 0)
	$facebook->getAccessToken();

// Use the graph API to get the logged is user from facebook :)
// If it fails, becouse token is to old, unset the sessions and reauth.
$user = $facebook->getUser();
