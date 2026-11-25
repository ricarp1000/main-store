<?php
/*
Use this URL to print data in browser:
http://localhost:80/main-store/src/list_users.php
*/

  session_start();
   if (!isset($_SESSION['user_id'])) {
      header('refresh:0;url=signin.html');
      exit();
   }


   include('../settings/database.php');

   // Get data frm users table
   $sql = "
      select 
          id,
          firstname || ' ' || lastname as fullname ,
          mobile_phone,
          email,
          case when status = true  then 'Active' else 'Inactive' end as status,
          url_photo 
       from 
          users
   "; 
   $res = pg_query($local_conn, $sql);
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
            --green: #3ddc97;
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
            padding: 48px clamp(16px, 5vw, 64px);
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

        .page {
            max-width: 1100px;
            margin: 0 auto;
            animation: enter .9s cubic-bezier(.2, .8, .2, 1) both;
        }

        @keyframes enter {
            from { opacity: 0; transform: translateY(30px) scale(.98); }
            to   { opacity: 1; transform: none; }
        }

        /* Barra superior: filtro + volver */
        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        .toolbar h1 {
            font-size: clamp(1.8rem, 5vw, 2.6rem);
            font-weight: 800;
            letter-spacing: -.03em;
            background: linear-gradient(120deg, #fff 20%, var(--amber) 55%, var(--pink));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        select {
            padding: 12px 20px;
            font-family: inherit;
            font-size: .95rem;
            font-weight: 600;
            color: var(--text);
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 999px;
            outline: none;
            cursor: pointer;
            transition: border-color .2s, box-shadow .2s;
        }
        select:focus {
            border-color: var(--pink);
            box-shadow: 0 0 0 4px rgba(255, 77, 141, .25);
        }
        select option { background: #1d1040; color: var(--text); }

        /* Tarjeta con la tabla */
        .table-card {
            background: rgba(255, 255, 255, .07);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, .16);
            border-radius: 28px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, .4);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 760px;
        }

        th {
            padding: 20px 18px;
            text-align: left;
            font-size: .85rem;
            font-weight: 600;
            color: var(--muted);
            background: rgba(255, 255, 255, .05);
            border-bottom: 1px solid rgba(255, 255, 255, .14);
        }

        td {
            padding: 16px 18px;
            font-size: .95rem;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            vertical-align: middle;
        }

        tr:last-child td { border-bottom: none; }
        tbody tr { transition: background .2s; }
        tbody tr:hover { background: rgba(255, 255, 255, .06); }

        /* Foto de usuario */
        td img.avatar {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid transparent;
            background: linear-gradient(var(--bg), var(--bg)) padding-box,
                        linear-gradient(135deg, var(--amber), var(--pink), var(--violet)) border-box;
            display: block;
        }
        .no-photo {
            color: var(--muted);
            font-size: .85rem;
        }

        .name { font-weight: 600; }
        .email, .phone { color: var(--muted); }

        /* Estado */
        .badge {
            display: inline-block;
            padding: 6px 14px;
            font-size: .8rem;
            font-weight: 600;
            border-radius: 999px;
        }
        .badge-active {
            color: var(--green);
            background: rgba(61, 220, 151, .14);
            border: 1px solid rgba(61, 220, 151, .4);
        }
        .badge-inactive {
            color: var(--pink);
            background: rgba(255, 77, 141, .14);
            border: 1px solid rgba(255, 77, 141, .4);
        }

        /* Botones de opciones (iconos) */
        .options {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
        .options a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background: rgba(255, 255, 255, .92);
            border-radius: 50%;
            transition: transform .2s, box-shadow .2s;
        }
        .options a:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 77, 141, .45);
        }
        .options a:focus-visible,
        .btn:focus-visible {
            outline: 3px solid var(--amber);
            outline-offset: 3px;
        }
        .options a img { width: 20px; height: 20px; }

        /* Menú inferior */
        .footer-menu {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-top: 28px;
            color: var(--muted);
            font-size: .95rem;
        }
        .btn {
            display: inline-block;
            padding: 14px 28px;
            font-weight: 600;
            text-decoration: none;
            color: #fff;
            background: linear-gradient(90deg, var(--violet), var(--pink));
            border-radius: 999px;
            box-shadow: 0 8px 24px rgba(255, 77, 141, .35);
            transition: transform .2s, box-shadow .2s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(255, 77, 141, .55);
        }

        @media (prefers-reduced-motion: reduce) {
            body::before, body::after, .page { animation: none; }
        }
    </style>
</head>
    <body>
      <div class="page">

       <div class="toolbar">
          <h1>Users</h1>
          <select>
               <option>List all users</option>
               <option>List active users only</option>
               <option>List inactive users only</option>
          </select>
       </div>

       <div class="table-card">
        <table>

            <tr>
               <th>photo</th> 
               <th>First name</th>
               <th>Email</th>
               <th>Mobile phone</th>
               <th>Status</th>
               <th>Options</th>
            </tr>
            <?php while($row = pg_fetch_assoc($res)) {?>
            <tr>
                <!-- here print data from database-->
               <td>
                  <?php 
                     if ($row['url_photo'] != "") { ?>
                          <img class="avatar" src="<?php echo htmlspecialchars($row['url_photo']); ?>" width="60" height="60"><?php }
                     else { ?>
                        <span class="no-photo">Sin foto</span>
                     <?php } ?>
               </td>
               <td class="name"><?= htmlspecialchars($row['fullname'])?></td>
               <td class="email"><?= htmlspecialchars($row['email'])?></td>
               <td class="phone"><?= htmlspecialchars($row['mobile_phone'])?></td>
               <td>
                  <span class="badge <?= $row['status'] == 'Active' ? 'badge-active' : 'badge-inactive' ?>">
                     <?= $row['status']?>
                  </span>
               </td>
               <td>
                <div class="options">
                  <a href="edit_user.php?id=<?php echo $row['id']; ?>">
                     <img src="icons/edit.png" alt="Edit user" width="20">
                  </a>
                  <a href="icons/search.png">
                     <img src="icons/search.png" alt="Search user" width="20">
                  </a>
                  <a href="delete_user.php?userID=<?= $row['id'] ?>">
                     <img src="icons/delete.png" alt="Delete user" width="20">
                  </a>
                  <a href="update_user.php?userID=<?= $row['id'] ?>&userStatus=<?= $row['status'] ?>">
                     <?php
                     if ($row['status'] == 'Active'){
                        echo"<img src='icons/inactive.png' alt='Inactive user' width='20'>";
                     }else{
                        echo"<img src='icons/active.png' alt='Active user' width='20'>";
                     }
                     ?>
                  </a>
                </div>
               </td>

            </tr>
             <?php }?>
            
       </table>
       </div>

       <div class="footer-menu">
          <span>menu:</span>
          <a class="btn" href='home.php' >Go to home</a>
       </div>

      </div>
    </body>
</html>