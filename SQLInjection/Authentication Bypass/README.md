# SQL Injection - Authentication Bypass
Este projeto demonstra uma vulnerabilidade de SQL Injection que permite o contorno do sistema de autenticação, permitindo que um atacante faça login sem conhecer a senha de um determinado usuário.

## Descrição
- A vulnerabilidade de SQL Injection ocorre quando uma aplicação permite que dados fornecidos pelo usuário sejam incluídos em uma consulta SQL de forma insegura. Neste caso, a vulnerabilidade está presente na autenticação de usuários, permitindo que um atacante bypass a verificação de senha e obtenha acesso ao sistema.

```php
if (isset($_POST['entrar'])) {
    $login = $_POST['login'];
    $senha = md5($_POST['senha']);

    $connect = mysqli_connect($hostname, $user, $pass, $dbname);

    if (!$connect) {
        die("Falha na conexão: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM users WHERE usuario = '$login' AND senha = '$senha'";
    $result = mysqli_query($connect, $query);

    if (mysqli_num_rows($result) > 0) {
        setcookie("login", $login);
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<h1>Login e/ou senha incorretos</h1>";
    }

    mysqli_free_result($result);
    mysqli_close($connect);
}
```

## Como a Vulnerabilidade Funciona
- Nesta aplicação, os dados de login ($login) e senha ($senha) fornecidos pelo usuário são diretamente incluídos na consulta SQL sem validação adequada. Um atacante pode explorar essa falha para contornar a autenticação e acessar o sistema sem precisar conhecer a senha real.

## Exploração
- Ao fornecer o seguinte input no campo de login:
```php
Administrator' OR '1'='1' --'
```
O código PHP gerará a seguinte consulta SQL:
```php
SELECT * FROM users WHERE usuario = 'Administrator' OR '1'='1' --' AND senha = '...';
```
Ao utilizar o payload ```' OR '1'='1' --'```, é criado um comentário na sintaxe SQL permitindo que o usuário consiga logar sem o uso de uma senha.

## Prevenção
- Para evitar essa vulnerabilidade, recomenda-se o uso de consultas preparadas (prepared statements) com parâmetros vinculados (bound parameters). Isso separa a lógica da consulta SQL dos dados fornecidos pelo usuário, eliminando a possibilidade de injeção de SQL.

Código:
```php
if (isset($_POST['entrar'])) {
    $login = $_POST['login'];
    $senha = md5($_POST['senha']);

    $connect = new mysqli($hostname, $user, $pass, $dbname);

    if ($connect->connect_error) {
        die("Falha na conexão: " . $connect->connect_error);
    }

    $stmt = $connect->prepare("SELECT * FROM users WHERE usuario = ? AND senha = ?");
    $stmt->bind_param("ss", $login, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        setcookie("login", $login);
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<h1>Login e/ou senha incorretos</h1>";
    }

    $stmt->close();
    $connect->close();
}
```

