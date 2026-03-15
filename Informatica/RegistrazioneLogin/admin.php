<?php
    require_once "conn.php";

    if (!isset($_SESSION["idSessione"]) && $_SESSION["idProfilo"] != 1) {
        header("Location: index.php");
        exit();
    }

    $users = usersDBtoClass();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
        logout();
        header("Location: index.php");
        exit();
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['elimina'])) {
        $id = $_POST['id'];
        foreach ($users as $user) {
            if ($user->getId() == $id) {
                if (!$user->delRecord()) {
                    echo "<script>alert('Errore durante l\'eliminazione dell\'utente.');</script>";
                } else {
                    echo "<script>alert('Utente eliminato con successo.');</script>";
                }
                break;
            }
        }
    }

    $users = usersDBtoClass();
    $sessioni = sessionDBtoClass();
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestione Profili</title>
        <link rel="stylesheet" href="css/admin.css">
    </head>
    <body>
        <form method="post">
            <button type="submit" name="logout">Logout</button>
        </form>

        <h1>Utenti</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome e Cognome</th>
                <th>Username</th>
                <th>Email</th>
                <th>Telefono</th>
                <th>Indirizzo</th>
                <th>CAP</th>
                <th>Città</th>
                <th>Profilo</th>
                <th>Abilitato</th>
                <th>Azioni</th>
            </tr>

            <?php
                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>" . $user->getId() . "</td>";
                    echo "<td>" . $user->getNome() . " " . $user->getCognome() . "</td>";
                    echo "<td>" . $user->getUsername() . "</td>";
                    echo "<td>" . $user->getEmail() . "</td>";
                    echo "<td>" . $user->getTelefono() . "</td>";
                    echo "<td>" . $user->getIndirizzo() . "</td>";
                    echo "<td>" . $user->getCAP() . "</td>";
                    echo "<td>" . $user->getCitta() . "</td>";
                    echo "<td>" . $user->getProfilo() . "</td>";
                    echo "<td>" . ($user->getAttivo() ? "Sì" : "No") . "</td>";
                    echo "<td>";
                    echo "
                        <form method='post'>
                            <input type='hidden' name='id' value='" . $user->getId() . "'>
                            <button class='delete-btn' name='elimina'>Elimina</button>
                        </form>
                        ";
                    echo "</tr>";
                }
            ?>
        </table>

        <h1>Accessi</h1>
        <table>
            <tr>
                <th>ID sessione</th>
                <th>Username</th>
                <th>Data Ora Login</th>
                <th>Data Ora Logout</th>
            </tr>

            <?php
                foreach ($sessioni as $sessione) {
                    echo "<tr>";
                    echo "<td>" . $sessione->getSessionId() . "</td>";
                    echo "<td>" . $sessione->getUsername() . "</td>";
                    echo "<td>" . $sessione->getDataLogin() . "</td>";
                    echo "<td>" . $sessione->getDataLogout() . "</td>";
                    echo "</tr>";
                }
            ?>
    </body>
</html>