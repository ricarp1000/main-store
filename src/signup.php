<?php
// Get payload from frontend
    $first_name = $_POST['Fname'];
    $last_name = $_POST['Lname'];
    $id = $_POST['idnumber'];
    $mobile_number = $_POST['Mphone'];
    $email = $_POST['E-mail'];
    $password = md5($_POST['pswd']);




echo  "<br>". $first_name; 
echo  "<br>". $last_name ;
echo  "<br>". $id  ;
echo  "<br>". $mobile_number  ;
echo  "<br>". $email ;
echo  "<br>". $password  ;