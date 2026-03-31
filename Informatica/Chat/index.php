<?php
    session_start();

    if (isset($_SESSION['user_id'])) {
        header('Location: chat.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['login'])) {
            $email = trim($_POST['email']);
            $token = trim($_POST['token']);
            if (isset($email) && isset($token)) {
                $error = "Inserisci email o token privato di accesso.";
            } else if (isset($email)) {
                
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>WEBLINK</title>
        <link rel="stylesheet" href="css/index.css">
    </head>
    <body>
        <img class="logo" src="storage/img/logo.svg">
        <div class="box">
            <div class="glass"></div>
            <form method="post">
                <input type="email" name="email" placeholder="Inserisci la tua email...">
                <p>O</p>
                <input type="text" name="token" placeholder="Inserisci il tuo token privato...">
                <input type="submit" name="login" value="Accedi">
            </form>
        </div>
        <?php 
            if (isset($error)) {
                echo "<div class='notifica'>" . $error . "</div>";
            }
        ?>
    </body>
</html>