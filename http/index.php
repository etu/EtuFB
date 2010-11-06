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
	<h1>Signed in</h1>
	<a href="/index2.php?code=<?=$facebook->code?>">Link to index2.php</a>
	<hr />
	<pre><?print_r($user);?></pre>
  </body>
</html>