<?php
    require_once 'conn.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['registrati'])) {
            $user = new UserObj(null, $_POST['nome'], $_POST['cognome'], $_POST['username'], $_POST['email'], $_POST['password'], $_POST['telefono'], $_POST['indirizzo'], $_POST['CAP'], $_POST['citta'], $_POST['profilo']);
            if (registrazione($user)) {
                header("Location: home.php");
                exit();
            } else {
                header("Location: index.php?error=registrazione_fallita");
                exit();
            }
        } else if (isset($_POST['accedi'])) {
            $user = new UserObj(null, null, null, $_POST['username'], null, $_POST['password'], null, null, null, null, null);
            if (accedi($user)) {
                header("Location: home.php");
                exit();
            } else {
                header("Location: index.php?error=accesso_fallito");
                exit();
            }
        }
    }
?>