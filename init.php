<?php
session_start();

require_once('../config.php');
require_once('../lib.php');

mysql_connect($db_host, $db_user, $db_pass) or die("database connect error");
mysql_select_db($db_table) or die("database selection error");

$fb_object = new EtuFB($fb_app_id, $fb_secret, $fb_canvas, $fb_eperms);
$fb_atoken = $_SESSION['accsess_token'];
$fb_code   = $_SESSION['code'];

if(strlen($fb_atoken) == 0 && !isset($_GET['code']))
	$err = '<script>window.top.location = "'.$fb_object->getAuthUrl().'";</script>';

if (strlen($fb_atoken) == 0 && isset($_GET['code'])) {
	$_SESSION['code'] = $_GET['code'];
	$fb_code = $_GET['code'];
	
	$get_token = $fb_object->getAccsessToken($_GET['code']);
	
	if(strlen($get_token) != 0) {
		$_SESSION['accsess_token'] = $get_token;
		$fb_atoken = $get_token;
	} else
		$err = 'Unable to get accsess token, try to reload the page and clear your cookies or something :(';
}

if($err)
	die($err);

$user = $fb_object->api('me', $fb_atoken);
