<?php
    session_start();

    $host = 'localhost';
    $dbname = 'scuola';
    $username = 'root';
    $password = '';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connessione al database fallita";
    }

    function registrazione($postData) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO utenti (nome, cognome, username, email, password, telefono, indirizzo, CAP, citta, profilo, attivo) VALUES (:nome, :cognome, :username, :email, :password, :telefono, :indirizzo, :CAP, :citta, :profilo, 1)");
        $stmt->bindParam(':nome', $postData['nome']);
        $stmt->bindParam(':cognome', $postData['cognome']);
        $stmt->bindParam(':username', $postData['username']);
        $stmt->bindParam(':email', $postData['email']);
        $stmt->bindParam(':password', $postData['password']);
        $stmt->bindParam(':telefono', $postData['telefono']);
        $stmt->bindParam(':indirizzo', $postData['indirizzo']);
        $stmt->bindParam(':CAP', $postData['CAP']);
        $stmt->bindParam(':citta', $postData['citta']);
        $stmt->bindParam(':profilo', $postData['profilo']);

        try {
            $stmt->execute();
            $_SESSION["nomeCognome"] = $postData['nome'] . " " . $postData['cognome'];
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    function accedi($postData) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM utenti WHERE username = :username AND attivo = 1");
        $stmt->bindParam(':username', $postData['username']);

        try {
            $stmt->execute();
            $utente = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }

        if ($utente && password_verify($postData['password'], $utente['password'])) {
            $_SESSION["nomeCognome"] = $utente['nome'] . " " . $utente['cognome'];
            return true;
        } else {
            return false;
        }
    }

    function getProfili() {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM profili");
        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
?>