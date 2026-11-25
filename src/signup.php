<?php
session_start();

// Si ya inició sesión, no necesita registrarse
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}

// Solo aceptar datos enviados desde el formulario
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signup.html');
    exit();
}

// get database connection
include('../settings/database.php');

// get payload from frontend
$first_name   = $_POST['fname'];
$last_name    = $_POST['lname'];
$ident_number = $_POST['idnumber'];
$mobile_phone = $_POST['mphone'];
$e_mail       = $_POST['email'];
$password     = password_hash($_POST['pswd'], PASSWORD_DEFAULT);
$photo_url    = 'photos/photo_default.png';

/**
 * Crea el usuario en la conexión indicada.
 * Devuelve: 'created', 'exists' o un texto con el error de PostgreSQL.
 */
function create_user($conn, $values) {
    $email = pg_escape_literal($conn, $values['email']);

    $check = pg_query($conn, "SELECT 1 FROM users WHERE email = $email");
    if ($check === false) {
        return 'error: ' . pg_last_error($conn);
    }
    if (pg_num_rows($check) > 0) {
        return 'exists';
    }

    $escaped = array();
    foreach ($values as $v) {
        $escaped[] = pg_escape_literal($conn, $v);
    }

    $sql = "INSERT INTO users(firstname, lastname, ident_number, mobile_phone, email, password, url_photo)
            VALUES (" . implode(', ', $escaped) . ")";

    if (pg_query($conn, $sql) === false) {
        return 'error: ' . pg_last_error($conn);
    }
    return 'created';
}

function label($result, $where) {
    if ($result === 'created') return "User created in $where.";
    if ($result === 'exists')  return "Email already exists in $where.";
    return "Error in $where -> " . $result;
}

// El orden debe coincidir con las columnas del INSERT
$user = array(
    'firstname'    => $first_name,
    'lastname'     => $last_name,
    'ident_number' => $ident_number,
    'mobile_phone' => $mobile_phone,
    'email'        => $e_mail,
    'password'     => $password,
    'url_photo'    => $photo_url
);

$local_result = create_user($local_conn, $user);
$supa_result  = $supa_conn ? create_user($supa_conn, $user) : 'error: no connection';

$message  = label($local_result, 'local') . "\n" . label($supa_result, 'supabase');
$redirect = ($local_result === 'created') ? 'signin.html' : 'signup.html';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main Store - Sign up</title>
</head>
<body>
<script>
    alert(<?= json_encode($message, JSON_HEX_TAG | JSON_HEX_AMP) ?>);
    window.location.href = <?= json_encode($redirect) ?>;
</script>
</body>
</html>