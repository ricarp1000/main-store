<?php
// Get Database connection
include('../settings/database.php');

// Get payload from frontend
    $first_name = $_POST['Fname'];
    $last_name = $_POST['Lname'];
    $id = $_POST['idnumber'];
    $mobile_number = $_POST['Mphone'];
    $email = $_POST['E-mail'];
    $password = md5($_POST['pswd']);

    // prepare query
$sql = "
    INSERT INTO 
    users (firstname, lastname, ident_number, email, password) 
    VALUES (
        '$first_name',
        '$last_name',
        '$id',
        '$email',
        '$password'
    );
";

// Execute query

$res = pg_query($local_conn, $sql);

if ($res) {
    echo "User registered successfully";
} else {
    echo "Error: User has not been created registering user: " 
    ;
}



