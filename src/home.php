<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('refresh:0;url=signin.html');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
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

            /* Tarjeta central */
            .card {
                width: 100%;
                max-width: 480px;
                padding: clamp(32px, 6vw, 56px);
                text-align: center;
                background: rgba(255, 255, 255, .07);
                backdrop-filter: blur(18px);
                -webkit-backdrop-filter: blur(18px);
                border: 1px solid rgba(255, 255, 255, .16);
                border-radius: 32px;
                box-shadow: 0 30px 80px rgba(0, 0, 0, .4);
                animation: enter .9s cubic-bezier(.2, .8, .2, 1) both;
            }

            @keyframes enter {
                from { opacity: 0; transform: translateY(30px) scale(.97); }
                to   { opacity: 1; transform: none; }
            }

            h3 {
                font-size: clamp(1.8rem, 5vw, 2.6rem);
                font-weight: 800;
                line-height: 1.15;
                letter-spacing: -.03em;
                margin-bottom: 12px;
                background: linear-gradient(120deg, #fff 20%, var(--amber) 55%, var(--pink));
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
            }

            .menu-label {
                color: var(--muted);
                font-size: .95rem;
                margin-bottom: 28px;
            }

            .actions {
                display: flex;
                flex-direction: column;
                gap: 14px;
            }

            .btn {
                display: block;
                padding: 15px 24px;
                font-size: 1rem;
                font-weight: 600;
                text-decoration: none;
                color: var(--text);
                border-radius: 999px;
                border: 1px solid rgba(255, 255, 255, .25);
                transition: transform .2s, background .2s, box-shadow .2s;
            }
            .btn:hover {
                background: rgba(255, 255, 255, .12);
                transform: translateY(-2px);
            }
            .btn:focus-visible {
                outline: 3px solid var(--amber);
                outline-offset: 3px;
            }

            .btn-primary {
                border: none;
                color: #fff;
                background: linear-gradient(90deg, var(--violet), var(--pink));
                box-shadow: 0 8px 24px rgba(255, 77, 141, .35);
            }
            .btn-primary:hover {
                background: linear-gradient(90deg, var(--violet), var(--pink));
                box-shadow: 0 12px 32px rgba(255, 77, 141, .55);
            }

            @media (prefers-reduced-motion: reduce) {
                body::before, body::after, .card { animation: none; }
            }
        </style>
    </head>
    <body>
        <div class="card">
            <h3>Welcome to main Store</h3>
            <p class="menu-label">Menu</p>

            <div class="actions">
                <a class="btn btn-primary" href="list_users.php">Go to list user</a>
                <a class="btn" href="signin.html">Back to sign in</a>
                <a class="btn" href="logout.php">Logout</a>
            </div>
        </div>
    </body>
</html>
