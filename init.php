<?php
session_start();

require_once('../config.php');
require_once('../lib.php');

$facebook = new EtuFB($fb);

$fb_atoken = $_SESSION['accsess_token'];
$fb_code   = $_SESSION['code'];

if(strlen($fb_atoken) == 0 && !isset($_GET['code']))
	$err = '<script>window.top.location = "'.$facebook->getAuthUrl().'";</script>';

if (strlen($fb_atoken) == 0 && isset($_GET['code'])) {
	$_SESSION['code'] = $_GET['code'];
	$fb_code = $_GET['code'];
	
	$get_token = $facebook->getAccsessToken($_GET['code']);
	
	if(strlen($get_token) != 0) {
		$_SESSION['accsess_token'] = $get_token;
		$fb_atoken = $get_token;
	} else
		$err = 'Unable to get accsess token, try to reload the page and clear your cookies or something :(';
}

if($err)
	die($err);

$user = $facebook->api('me', $fb_atoken);
