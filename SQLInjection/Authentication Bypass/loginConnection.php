<?php
$hostname = '127.0.0.1';
$user     = 'hacker';
$pass     = 'senhatop1337';
$dbname   = 'sqli';

if (isset($_POST['entrar'])) {
    $login = $_POST['login'];
    $senha = md5($_POST['senha']);

    $connect = mysqli_connect($hostname, $user, $pass, $dbname);

    if (!$connect) {
        die("Falha na conexão: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM users WHERE usuario = '$login' AND senha = '$senha'";
    $result = mysqli_query($connect, $query);

    if (mysqli_num_rows($result) <= 0) {
        echo "<h1>Login e/ou senha incorretos</h1>";
        die();
    } else {
        setcookie("login", $login);
        header("Location: dashboard.php");
        exit();
    }

    mysqli_free_result($result);
    mysqli_close($connect);
}
?>
