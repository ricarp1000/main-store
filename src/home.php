<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: signin.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Store - Inicio</title>

</head>
<body>

    <div class="home-box">
        <h1>Welcome to Main Store</h1>
        <p>Has iniciado sesión correctamente en el sistema.</p>
        
        <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
    </div>

</body>
</html>

