<?php

include "../settings/database.php";

session_start();

$email = $_POST['email'];
$password = md5($_POST['password']);

$sql_check_email = "
SELECT
    id,
    firstname,
    lastname,
    email
FROM users
WHERE email = '$email'
AND password = '$password'
";

$res = pg_query($local_conn, $sql_check_email);

$total = pg_num_rows($res);

$row = pg_fetch_assoc($res);

if($total > 0){

    $_SESSION['user_id'] = $row['id'];

    header("Location: home.php");
    exit();

}else{

    echo "<script>
    alert('Correo o contraseña incorrectos');
    window.location='signin.html';
    </script>";

}

?>




