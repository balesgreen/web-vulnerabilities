# SQL Injection - Authentication Bypass
Este projeto demonstra uma vulnerabilidade de SQL Injection que permite o contorno do sistema de autenticação, permitindo que um atacante faça login sem conhecer a senha de um determinado usuário.

## Descrição
- A vulnerabilidade de SQL Injection ocorre quando uma aplicação permite que dados fornecidos pelo usuário sejam incluídos em uma consulta SQL de forma insegura. Neste caso, a vulnerabilidade está presente na autenticação de usuários, permitindo que um atacante bypass a verificação de senha e obtenha acesso ao sistema.

```php
<?php
$hostname = '127.0.0.1';
$user     = 'root';
$pass     = 'senhatop1337';
$dbname   = 'sqli';

// Verificar se o formulário foi submetido
if (isset($_POST['entrar'])) {
    $login = $_POST['login'];
    $senha = md5($_POST['senha']); // Note que o uso de md5() é desencorajado; prefira bcrypt ou outras funções de hashing mais seguras.

    // Conectar ao banco de dados
    $connect = mysqli_connect($hostname, $user, $pass, $dbname);

    // Verificar conexão
    if (!$connect) {
        die("Falha na conexão: " . mysqli_connect_error());
    }

    // Construir consulta SQL vulnerável
    $query = "SELECT * FROM users WHERE usuario = '$login' AND senha = '$senha'";
    $result = mysqli_query($connect, $query);

    // Verificar se o usuário foi encontrado
    if (mysqli_num_rows($result) > 0) {
        setcookie("login", $login);
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<h1>Login e/ou senha incorretos</h1>";
    }

    // Fechar conexão
    mysqli_free_result($result);
    mysqli_close($connect);
}
?>
```

