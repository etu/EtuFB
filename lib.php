<?php
/**
 * EtuFb
 *
 * @author Elis Axelsson <elis.axelsson@gmail.com>
 * @copyright GNU Public License v3
 */
class EtuFB {
	private $api_id;   ///< Api Id is a value from Facebook
	private $secret;   ///< Secret is another value from Facebook
	
	private $url;      ///< Url to the app
	private $redirect; ///< Where to redirect after authing
	private $eperms;   ///< Extended permissions the app wants
	
	protected $debug;  ///< Debugmode or not
	
	public $code;      ///< Auth code for current user
	public $token;     ///< Auth token for current user
	
	/**
	 * Construct function, sets up the class, tries to fetch code and token from Sessions/GET.
	 *
	 * @param $fb array with configoptions from the configfile
	 * @param $debug bool true or false for debug
	 */
	public function __construct($fb, $debug = False) {
		$this->api_id   = $fb['api_id'];
		$this->secret   = $fb['secret'];
		$this->url      = $fb['url'];
		$this->redirect = $fb['url'].$fb['redirect'];
		$this->eperms   = $fb['eperms'];
		
		$this->debug    = $debug;
		
		if(strlen($_SESSION['code']) !== 0)
			$this->code = $_SESSION['code'];	
		elseif(strlen($_GET['code']) !== 0)
			$this->code = $_GET['code'];
		else
			$this->code = '';
		
		if(strlen($_SESSION['token']) !== 0)
			$this->token = $_SESSION['token'];
		else
			$this->token = '';
		
		
		if($this->debug) {
			// Internet Explorer Emulation
			unset($_SESSION['code']);
			unset($_SESSION['token']);
			
			// Without this permission you will not be able to fetch accsesstoken nor do api-calls
			if(ini_get('allow_url_fopen') === False)
				echo 'You have to enable <em>allow_url_fopen</em>, file_get_contents have to be able to accsess URLs to fetch the users accsess_token.';
			
			// It's always a good idea to disable /display_errors/ in a live enviorment
			if(ini_get('display_errors') === '1')
				echo 'You have to disable <em>display_errors</em>, else it will not work when the access_token run out of time.';
		} else {
			if(ini_get('allow_url_fopen') === False)
				ini_set('allow_url_fopen', True);
			
			if(ini_get('display_errors') === '1')
				ini_set('display_errors', 'Off');
		}
	}
	
	/**
	 * Returns the URL you should send the user to, to authenticate and get a code. This also requests for extra permissions.
	 *
	 * @return Auth URL for the current session
	 */
	private function getAuthUrl() {
		$auth_url = 'https://graph.facebook.com/oauth/authorize'.
			'?client_id='.$this->api_id.
			'&redirect_uri='.$this->redirect.
			'&scope='.$this->eperms;
		return $auth_url;
	}
	
	/**
	 * Unsets all sessions needed for authing, getting the authUrl, kills the runtime and prints a Javascript to reauth.
	 */
	public function reAuth() {
		unset($_SESSION['code']);
		unset($_SESSION['token']);
		die('<script>window.top.location="'.$this->getAuthUrl().'";</script>');
	}
	
	/**	
	 * Tries to get a access token using the code, if it succseed it will return the accesstoken.
	 * If it fails(usaly becouse the code is too old) it will reauthenticate the user.
	 *
	 * @see reAuth
	 * @return Result from request, or reAuthing
	 */
	public function getAccessToken() {
		$access_token_url = 'https://graph.facebook.com/oauth/access_token'.
			'?client_id='.$this->api_id.
			'&redirect_uri='.$this->redirect.
			'&client_secret='.$this->secret.
			'&code='.$this->code;
		
		$result = file_get_contents($access_token_url);
		
		// If the fetching of an access token fails... ReAuth the user!
		if($result === false) {
			$this->reAuth();
		} else {
			$this->token       = $result;
			$_SESSION['code']  = $this->code;
			$_SESSION['token'] = $this->token;
			
			return $result;
		}
	}
	
	/**
	 * Just a dumb version of api(). Fetches the user from facebook.
	 *
	 * @see reAuth
	 * @return Json Encoded result on success, else it will reAuth
	 */
	public function getUser() {
		$result = file_get_contents('https://graph.facebook.com/me?'.$this->token);
		
		if($result === false)
			$this->reAuth();	
		else
			return json_decode($result);
	}
	
	/**
	 * Needs moar work to get it working with all kinds of graph api-calls.
	 *
	 * @param $call String with call
	 * @return Json Encoded result
	 */
	public function api($call) {
		$url = 'https://graph.facebook.com/'.$call.'?'.$this->token;
		$result = file_get_contents($url);
		return json_decode($result);
	}
}
