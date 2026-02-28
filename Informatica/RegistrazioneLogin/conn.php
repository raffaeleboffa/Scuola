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
        $stmt->bindParam(':password', password_hash($postData['password'], PASSWORD_DEFAULT));
        $stmt->bindParam(':telefono', $postData['telefono']);
        $stmt->bindParam(':indirizzo', $postData['indirizzo']);
        $stmt->bindParam(':CAP', $postData['CAP']);
        $stmt->bindParam(':citta', $postData['citta']);
        $stmt->bindParam(':profilo', $postData['profilo']);

        try {
            $stmt->execute();

            // Recupera l'ID dell'utente appena registrato
            $stmt = $conn->prepare("SELECT id FROM utenti WHERE username = :username");
            $stmt->bindParam(':username', $postData['username']);
            $stmt->execute();

            $utente = $stmt->fetch(PDO::FETCH_ASSOC);

            // Inserimento nuova sessione per l'utente appena registrato
            $stmt = $conn->prepare("INSERT INTO sessioni (utente, data_login, data_logout) VALUES (:utente, NOW(), NULL)");
            $stmt->bindParam(':utente', $utente['id']);
            $stmt->execute();

            // Recupera l'ID della sessione appena creata
            $stmt = $conn->prepare("SELECT id FROM sessioni WHERE utente = :utente ORDER BY data_login DESC LIMIT 1");
            $stmt->bindParam(':utente', $utente['id']);
            $stmt->execute();

            $sessione = $stmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION["idSessione"] = $sessione['id'];

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
            // Inserimento nuova sessione per l'utente che ha effettuato il login
            $stmt = $conn->prepare("INSERT INTO sessioni (utente, data_login, data_logout) VALUES (:utente, NOW(), NULL)");
            $stmt->bindParam(':utente', $utente['id']);
            $stmt->execute();

            // Recupera l'ID della sessione appena creata
            $stmt = $conn->prepare("SELECT id FROM sessioni WHERE utente = :utente ORDER BY data_login DESC LIMIT 1");
            $stmt->bindParam(':utente', $utente['id']);
            $stmt->execute();

            $sessione = $stmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION["idSessione"] = $sessione['id'];

            return true;
        } else {
            return false;
        }
    }

    function logout() {
        global $conn;
        if (isset($_SESSION["idSessione"])) {
            $query = $conn->prepare("UPDATE sessioni SET data_logout = NOW() WHERE id = :idSessione");
            $query->bindParam(':idSessione', $_SESSION["idSessione"]);
            $query->execute();

            session_destroy();
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