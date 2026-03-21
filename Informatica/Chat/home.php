<?php
    require_once "conn.php";

    if (!isset($_SESSION["idSessione"])) {
        header("Location: index.php");
        exit();
    } else if (isset($_SESSION["idSessione"]) && $_SESSION["idProfilo"] == 1) {
        header("Location: admin.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
        logout();
        header("Location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <link rel="stylesheet" href="css/home.css">
    </head>
    <body>
        <form method="post">
            <button type="submit" name="logout">Logout</button>
        </form>
    </body>
</html>