<?php
// Get Database connection
include('../settings/database.php');

// Get payload from frontend
    $first_name = $_POST['Fname'];
    $last_name = $_POST['Lname'];
    $id = $_POST['idnumber'];
    $mobile_number = $_POST['Mphone'];
    $email = $_POST['email'];
    $password = MD5($_POST['pswd']);

$sql_check_email = "SELECT * FROM users WHERE email = '$email'";
$res = pg_query($local_conn, $sql_check_email);
$total = pg_num_rows($res);
if ($total < 1) {

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
        echo "<script>alert('User has been created successfully!');</script>";
        header('refresh: 0; url=signin.html');
    } else {
        echo "Error: User has not been created!";
    }
}else {
    echo "<script>alert('User email already exists! try again !!!');</script>";
    header('refresh: 0; url=signup.html');
}

