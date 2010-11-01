<?php
/***
 * File: ./init.php
 * This file is an example of how you initializes the lib, auths the user, get accsess_token, and fetches the user.
 */

session_start();

require_once('../config.php');
require_once('../lib.php');

$facebook = new EtuFB($fb);

$sess['code']   = $_SESSION['code'];

if (strlen($sess['code']) == 0 AND !isset($_GET['code']))
	die('<script>window.top.location = "'.$facebook->getAuthUrl().'";</script>');

if (strlen($sess['code']) != 0 AND isset($_GET['code'])) {
	$sess['code']     = $_GET['code'];
	$_SESSION['code'] = $_GET['code'];
	
	$get_token = $facebook->getAccsessToken($_GET['code']);
	
	$_SESSION['accsess_token'] = $get_token;
	$sess['accsess_token']     = $get_token;
}

$user = $facebook->api('me', $sess['accsess_token']);
