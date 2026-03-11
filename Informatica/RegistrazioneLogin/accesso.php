<?php
    require_once 'conn.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['registrati'])) {
            $user = new UserObj($_POST);
            if (registrazione($user)) {
                if ($_SESSION["idProfilo"] == 1) {
                    header("Location: admin.php");
                    exit();
                } else {
                    header("Location: home.php");
                    exit();
                }
            } else {
                header("Location: index.php?error=registrazione_fallita");
                exit();
            }
        } else if (isset($_POST['accedi'])) {
            $user = new UserObj($_POST);
            if (accedi($user)) {
                if ($_SESSION["idProfilo"] == 1) {
                    header("Location: admin.php");
                    exit();
                } else {
                    header("Location: home.php");
                    exit();
                }
            } else {
                header("Location: index.php?error=accesso_fallito");
                exit();
            }
        }
    }
?>