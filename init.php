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

$facebook = new EtuFB($fb);

if (strlen($facebook->code) == 0)
	$facebook->reAuth();

if (strlen($facebook->token) == 0)
	$facebook->getAccessToken();

// Use the graph API to get the logged is user from facebook :)
$user = $facebook->getUser();
