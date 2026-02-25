<?php
    include 'conn.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['registrati'])) {
            if (registrazione($_POST)) {
                header("Location: home.php");
                exit();
            } else {
                header("Location: index.php?error=registrazione_fallita");
                exit();
            }
        } else if (isset($_POST['accedi'])) {
            if (accedi($_POST)) {
                header("Location: home.php");
                exit();
            } else {
                header("Location: index.php?error=accesso_fallito");
                exit();
            }
        }
    }
?>