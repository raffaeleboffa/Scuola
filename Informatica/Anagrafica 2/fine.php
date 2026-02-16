<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $telefono = $_POST['telefono'];
        $indirizzo = $_POST['indirizzo'];
        $numeroCivico = $_POST['numeroCivico'];
        $citta = $_POST['citta'];

        if (isset($_POST['active_hobbies'])) {
            $hobbies = $_POST['hobbies'];
        }

        if (isset($_POST['active_sports'])) {
            $sports = $_POST['sports'];
        }
    } else {
        header('Location: index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Anagrafica</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="anagrafica">
            <h1>Anagrafica</h1>
            <table>
                <tr>
                    <th>Nome</th>
                    <td><?php echo $nome ?></td>
                </tr>
                <tr>
                    <th>Cognome</th>
                    <td><?php echo $cognome ?></td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td><?php echo $username ?></td>
                <tr>
                    <th>Email</th>
                    <td><?php echo $email ?></td>
                </tr>
                <tr>
                    <th>Password</th>
                    <td><?php echo $password ?></td>
                </tr>
                <tr>
                    <th>Telefono</th>
                    <td><?php echo $telefono ?></td>
                </tr>
                <tr>
                    <th>Indirizzo</th>
                    <td><?php echo $indirizzo ?></td>
                </tr>
                <tr>
                    <th>Numero Civico</th>
                    <td><?php echo $numeroCivico ?></td>
                </tr>
                <tr>
                    <th>Città</th>
                    <td><?php echo $citta ?></td>
                </tr>

                <?php
                    if (isset($hobbies)) {
                        echo "<tr><th>Hobbies</th><td>$hobbies</td></tr>";
                    }

                    if (isset($sports)) {
                        echo "<tr><th>Sport</th><td>$sports</td></tr>";
                    }
                ?>
            </table>
        </div>        
    </body>
</html>