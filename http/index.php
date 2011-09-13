<?php
require_once('../init.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
	<title>FBAPP</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  </head>
  <body>
	<h1>/http/index.php: Authed</h1>
	<a href="/index2.php?code=<?php echo $facebook->code; ?>">Link to index2.php</a>
	<hr />
	<pre><?php print_r($user);?></pre>
  </body>
</html>