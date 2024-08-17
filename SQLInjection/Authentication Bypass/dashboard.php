<!doctype html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width" />
    <title>Dashboard</title>
  </head>
<body>
<?php
    $login_cookie = $_COOKIE['login'];
    if(isset($login_cookie)) {
	    echo "Bem-vindo, $login_cookie!";
    } else {
	    echo "Desculpe, mas você não tem acesso a essas informações <br>";
	    echo "Tente novamente.";
    }
?>
<body>
</html>
