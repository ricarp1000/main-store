<?php

// http://localhost/main-store/src/edit_user.php 

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.html');
    exit();
}

include('../settings/database.php');

$id = $_GET['id'];   // el número que llegó en la URL

$sql = 'SELECT * FROM users WHERE id = $1';
$result = pg_query_params($local_conn, $sql, array($id));
$user = pg_fetch_assoc($result);   // una sola fila: el usuario a editar

if (!$user) {
    header('Location: list_users.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuario</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #130a2a;
            --violet: #7c3aed;
            --pink: #ff4d8d;
            --amber: #ffb547;
            --text: #f6f2ff;
            --muted: #b9aedb;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            font-family: "Sora", system-ui, sans-serif;
            color: var(--text);
            background: var(--bg);
            overflow-x: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        /* Luces de fondo animadas */
        body::before,
        body::after {
            content: "";
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            z-index: -1;
            animation: float 14s ease-in-out infinite alternate;
        }
        body::before {
            width: 520px; height: 520px;
            background: var(--violet);
            top: -140px; left: -120px;
            opacity: .6;
        }
        body::after {
            width: 460px; height: 460px;
            background: var(--pink);
            bottom: -140px; right: -100px;
            opacity: .45;
            animation-delay: -6s;
        }

        @keyframes float {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(60px, 40px) scale(1.15); }
        }

        br { display: none; }

        h1 {
            font-size: clamp(1.8rem, 5vw, 2.8rem);
            font-weight: 800;
            letter-spacing: -.03em;
            margin-bottom: 24px;
            text-align: center;
            background: linear-gradient(120deg, #fff 20%, var(--amber) 55%, var(--pink));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        form {
            width: 100%;
            max-width: 460px;
            padding: clamp(28px, 5vw, 48px);
            background: rgba(255, 255, 255, .07);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, .16);
            border-radius: 32px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, .4);
            display: flex;
            flex-direction: column;
            animation: enter .9s cubic-bezier(.2, .8, .2, 1) both;
        }

        @keyframes enter {
            from { opacity: 0; transform: translateY(30px) scale(.97); }
            to   { opacity: 1; transform: none; }
        }

        form label {
            font-size: .85rem;
            font-weight: 600;
            color: var(--muted);
            margin: 18px 0 8px;
        }
        form label:first-of-type { margin-top: 0; }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 14px 18px;
            font-family: inherit;
            font-size: 1rem;
            color: var(--text);
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 16px;
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus {
            border-color: var(--pink);
            background: rgba(255, 255, 255, .12);
            box-shadow: 0 0 0 4px rgba(255, 77, 141, .25);
        }

        /* Foto actual */
        form p {
            font-size: .85rem;
            font-weight: 600;
            color: var(--muted);
            margin: 22px 0 10px;
        }
        form img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid transparent;
            background: linear-gradient(var(--bg), var(--bg)) padding-box,
                        linear-gradient(135deg, var(--amber), var(--pink), var(--violet)) border-box;
            box-shadow: 0 12px 30px rgba(255, 77, 141, .35);
            align-self: center;
        }

        /* Selector de archivo */
        input[type="file"] {
            width: 100%;
            padding: 12px 14px;
            font-family: inherit;
            font-size: .9rem;
            color: var(--muted);
            background: rgba(255, 255, 255, .08);
            border: 1px dashed rgba(255, 255, 255, .3);
            border-radius: 16px;
            cursor: pointer;
        }
        input[type="file"]::file-selector-button {
            margin-right: 14px;
            padding: 8px 18px;
            font-family: inherit;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(90deg, var(--violet), var(--pink));
            border: none;
            border-radius: 999px;
            cursor: pointer;
        }

        button {
            margin-top: 30px;
            padding: 15px 24px;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(90deg, var(--violet), var(--pink));
            border: none;
            border-radius: 999px;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(255, 77, 141, .35);
            transition: transform .2s, box-shadow .2s;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(255, 77, 141, .55);
        }

        form a {
            margin-top: 14px;
            padding: 14px 24px;
            text-align: center;
            text-decoration: none;
            font-weight: 600;
            color: var(--text);
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 999px;
            transition: transform .2s, background .2s;
        }
        form a:hover {
            background: rgba(255, 255, 255, .12);
            transform: translateY(-2px);
        }

        button:focus-visible,
        form a:focus-visible {
            outline: 3px solid var(--amber);
            outline-offset: 3px;
        }

        @media (prefers-reduced-motion: reduce) {
            body::before, body::after, form { animation: none; }
        }
    </style>
</head>
<body>
    <h1>Editar usuario</h1>

    <form action="update_user.php" method="post" enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">

        <label>Nombre</label><br>
        <input type="text" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required><br><br>

        <label>Apellido</label><br>
        <input type="text" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required><br><br>

        <label>Correo</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>

        <label>Celular</label><br>
        <input type="text" name="mobile_phone" value="<?php echo htmlspecialchars($user['mobile_phone']); ?>" required><br><br>

        <?php if ($user['url_photo'] != "") { ?>
            <p>Foto actual:</p>
            <img src="<?php echo htmlspecialchars($user['url_photo']); ?>" width="120"><br><br>
        <?php } ?>

        <label>Foto (JPG o PNG)</label><br>
        <input type="file" name="photo" accept=".jpg,.jpeg,.png"> <br><br>

        <button type="submit">Guardar</button>
        <a href="list_users.php">Cancelar</a>
    </form>
</body>
</html>