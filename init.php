<?php
/***
 * File: ./init.php
 * This file is an example of how you initializes the lib, auths the user, get accsess_token, and fetches the user.
 */

session_start();

require_once('../config.php');
require_once('../lib.php');

$facebook = new EtuFB($fb);

// Retrive the code from the session from the sessioncookie (This will not happen with IE)
$sess['code']   = $_SESSION['code'];

// If the code is _not_ set in the SESSION or in the GET, the program will break and redirect the user to the authpage
if (strlen($sess['code']) != 0 OR isset($_GET['code']))
	die('<script>window.top.location = "'.$facebook->getAuthUrl().'";</script>');
else { // And... When the redirection is done, and the user is authed. The prossedure continues to get a accsess_token
	$sess['code']     = $_GET['code'];
	$_SESSION['code'] = $_GET['code'];
	
	$get_token = $facebook->getAccsessToken($_GET['code']); // If it fails to gets accsess_token, becose the code is old. It will die and reauth the user.
	
	$_SESSION['accsess_token'] = $get_token;
	$sess['accsess_token']     = $get_token;
}
// Use the graph API to get the logged is user from facebook :)
$user = $facebook->api('me', $sess['accsess_token']);
