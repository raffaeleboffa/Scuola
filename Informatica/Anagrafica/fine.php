<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];

        /*
        echo "<h1>Informazioni inserite</h1>";
        echo "<p><strong>Nome:</strong> $nome</p>";
        echo "<p><strong>Cognome:</strong> $cognome</p>";
        echo "<p><strong>Email:</strong> $email</p>";
        echo "<p><strong>Telefono:</strong> $telefono</p>";
        */

        if (isset($_POST['active_hobbies'])) {
            $hobbies = $_POST['hobbies'];
            // echo "<p><strong>Hobbies:</strong> $hobbies</p>";
        }

        if (isset($_POST['active_sports'])) {
            $sports = $_POST['sports'];
            //echo "<p><strong>Sport:</strong> $sports</p>";
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
                    <th>Email</th>
                    <td><?php echo $email ?></td>
                </tr>
                <tr>
                    <th>Telefono</th>
                    <td><?php echo $telefono ?></td>
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