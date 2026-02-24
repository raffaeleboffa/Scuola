<?php
    include 'conn.php';
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['registrati'])) {
            $nome = $_POST['nome'];
            $cognome = $_POST['cognome'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $telefono = $_POST['telefono'];
            $indirizzo = $_POST['indirizzo'];
            $CAP = $_POST['CAP'];
            $citta = $_POST['citta'];

            $stmt = $conn->prepare("INSERT INTO utenti (nome, cognome, username, email, password, telefono, indirizzo, CAP, citta, attivo) VALUES (:nome, :cognome, :username, :email, :password, :telefono, :indirizzo, :CAP, :citta, 1)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':cognome', $cognome);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':indirizzo', $indirizzo);
            $stmt->bindParam(':CAP', $CAP);
            $stmt->bindParam(':citta', $citta);

            try {
                $stmt->execute();

                $_SESSION["nomeCognome"] = $nome . " " . $cognome;

                header("Location: index.php");
                exit();
            } catch (PDOException $e) {
                header("Location: index.php?error=registrazione_fallita");
                exit();
            }
        } else if (isset($_POST['accedi'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $conn->prepare("SELECT * FROM utenti WHERE username = :username AND attivo = 1");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $utente = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($utente && password_verify($password, $utente['password'])) {
                $_SESSION["username"] = $utente['username'];

                $result = $conn->query("SELECT nome, cognome FROM utenti WHERE username = '" . $utente['username'] . "'");
                $row = $result->fetch(PDO::FETCH_ASSOC);

                $_SESSION["nomeCognome"] = $row['nome'] . " " . $row['cognome'];

                header("Location: home.php");
                exit();
            } else {
                header("Location: index.php?error=accesso_fallito");
                exit();
            }
        }
    }
?>