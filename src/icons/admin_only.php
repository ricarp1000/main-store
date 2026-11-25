<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sin sesión: al login
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.html');
    exit();
}

// Con sesión pero sin ser admin: de vuelta al home
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: home.php');
    exit();
}