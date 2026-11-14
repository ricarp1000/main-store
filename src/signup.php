<?php

include "../settings/database.php";

$first_name = $_POST['fname'];
$last_name = $_POST['lname'];
$identification_number = $_POST['idnumber'];
$mobile_phone = $_POST['mphone'];
$email = $_POST['email'];
$password = md5($_POST['passw']);

$sql = "
INSERT INTO users
(
    firstname,
    lastname,
    ident_number,
    mobile_phone,
    email,
    password
)
VALUES
(
    '$first_name',
    '$last_name',
    '$identification_number',
    '$mobile_phone',
    '$email',
    '$password'
)";

$result = pg_query($local_conn, $sql);

if($result){
    echo "<script>alert('Usuario registrado correctamente');</script>";
    header('Refresh:1; url=signin.html');
}else{
    echo "Error al registrar usuario";
}

?>