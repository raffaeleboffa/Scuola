<?php
    session_start();

    if (isset($_SESSION["nomeCognome"])) {
        header("Location: home.php");
        exit();
    }

    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        if ($error == 'registrazione_fallita') {
            echo "<script>alert('Registrazione fallita. Riprova. Se il problema persiste prova a cambiare username.');</script>";
        } elseif ($error == 'accesso_fallito') {
            echo "<script>alert('Accesso fallito. Controlla username e password.');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Anagrafica</title>
        <link rel="stylesheet" href="css/index.css">
    </head>
    <body>
        <form action="accesso.php" method="post">
            <h1>Registrati</h1>
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" required>
            <label for="cognome">Cognome</label>
            <input type="text" name="cognome" id="cognome" required>

            <hr>

            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required min="8">
            <label for="telefono">Telefono</label>
            <input type="text" name="telefono" id="telefono" required>

            <hr>

            <label for="indirizzo">Indirizzo:</label>
            <input type="text" name="indirizzo" id="indirizzo" required>
            <label for="CAP">CAP:</label>
            <input type="text" name="CAP" id="CAP" required>
            <label for="citta">Città:</label>
            <input type="text" name="citta" id="citta" required>

            <hr>

            <input type="submit" name="registrati" value="Registrati">
        </form>

        <form action="accesso.php" method="post">
            <h1>Accedi</h1>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required min="8">

            <hr>

            <input type="submit" name="accedi" value="Accedi">
        </form>
    </body>
</html>