<?php
    include "conn.php";    
    $result = $conn->query("
        SELECT
        u.nome nome, u.cognome cognome, u.username username, u.email email,
        u.telefono telefono, u.indirizzo indirizzo, u.CAP CAP, u.citta citta,
        u.attivo attivo, p.tipo profilo
        FROM utenti u JOIN profili p
        ON u.profilo = p.id
    ")->fetchAll(PDO::FETCH_ASSOC);

    if (!isset($_SESSION["nomeCognome"])) {
        header("Location: index.php");
        exit();
    }
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
        <h1>Benvenuto <?php echo $_SESSION["nomeCognome"]; ?></h1>
        <a href="logout.php">Esci</a>
        <table>
            <tr>
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
                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['nome'] . " " . $row['cognome'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['telefono'] . "</td>";
                    echo "<td>" . $row['indirizzo'] . "</td>";
                    echo "<td>" . $row['CAP'] . "</td>";
                    echo "<td>" . $row['citta'] . "</td>";
                    echo "<td>" . $row['profilo'] . "</td>";
                    echo "<td>" . ($row['attivo'] ? "Sì" : "No") . "</td>";
                    echo "</tr>";
                }
            ?>
        </table>
    </body>
</html>