<?php
session_start();

// Si ya inició sesión, ir al home
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}

// Solo aceptar datos enviados desde el formulario
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signin.html');
    exit();
}

include "../settings/database.php";

$email    = $_POST['email'];
$password = $_POST['pswd'];

/**
 * Busca el usuario por email y verifica la contraseña con password_verify.
 * Devuelve el usuario (array) o null.
 */
function find_user($conn, $email, $password) {
    if (!$conn) return null;

    $res = pg_query_params(
        $conn,
        "SELECT id, firstname, lastname, email, password FROM users WHERE email = $1",
        array($email)
    );

    if ($res === false || pg_num_rows($res) < 1) return null;

    $row = pg_fetch_assoc($res);

    if (password_verify($password, $row['password'])) {
        return $row;
    }
    return null;
}

// 1) Buscar primero en local, 2) si no está, probar en Supabase
$user = find_user($local_conn, $email, $password);
if (!$user) {
    $user = find_user($supa_conn, $email, $password);
}

if ($user) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    header('Location: home.php');
    exit();
}

// Credenciales incorrectas
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main Store - Sign in</title>
</head>
<body>
<script>
    alert('Incorrect email or password. Try again !!!');
    window.location.href = 'signin.html';
</script>
</body>
</html>