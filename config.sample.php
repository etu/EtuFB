<?php
/***
 * File: ./config.sample.php
 * Configfile
 *
 ** $fb['api_id']
 * Your api-id, the one you gets from facebook.
 *
 ** $fb['secret']
 * Your api-secret, you get this from facebook too ;)
 *
 ** $fb['url']
 * The canvaspage for your application.
 *
 ** $fb['redirect']
 * The url where you should get redirected after you get authentication.
 *
 ** $fb['eperms']
 * Extended permissions you want to require from the user, for example: publish_stream,email,sms
 */

$fb['api_id']   = '<your api-id>';
$fb['secret']   = '<your api-secret>';
$fb['url']      = 'http://apps.facebook.com/<your appcanvas here>/';
$fb['redirect'] = $fb['url'].'?page=oauth_redirect';
$fb['eperms']   = '';
