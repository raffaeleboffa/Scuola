<?php
    require_once "conn.php";

    if (!isset($_SESSION["idSessione"])) {
        header("Location: index.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
        logout();
        header("Location: index.php");
        exit();
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salva'])) {
        foreach ($users as $user) {
            if ($user->getStato() == "modificato") {
                $user->modRecord();
            } else if ($user->getStato() == "cancellato") {
                $user->delRecord();
            }
        }
    }

    $users = usersDBtoClass();
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestione Profili</title>
        <link rel="stylesheet" href="css/home.css">
    </head>
    <body>
        <div class="header">
            <form method="post">
                <button type="submit" name="logout">Logout</button>
            </form>

            <form method="post">
                <button type="submit" name="salva">Salva</button>
            </form>
        </div>

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
                    echo "</tr>";
                }
            ?>
        </table>
    </body>
</html>