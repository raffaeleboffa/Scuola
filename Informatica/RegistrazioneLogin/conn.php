<?php
    session_start();
    require_once 'class/UserObj.php';
    require_once 'class/SessionObj.php';

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

    function registrazione($utente) {
        global $conn;
        if (!$utente->addRecord()) {
            return false;
        }

        $utente->getDataLost();

        try {
            // Inserimento nuova sessione per l'utente appena registrato
            $stmt = $conn->prepare("INSERT INTO sessioni (id_sessione, utente, data_login, data_logout) VALUES (:id_sessione, :utente, NOW(), NULL)");
            $stmt->bindParam(':id_sessione', session_id());
            $stmt->bindParam(':utente', $utente->getId());
            $stmt->execute();

            // ID del record della sessione appena creata e ID del profilo dell'utente appena registrato
            $_SESSION["idSessione"] = session_id();
            $_SESSION["idProfilo"] = $utente->getProfilo();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    function accedi($utente) {
        global $conn;
        $users = usersDBtoClass(1);

        foreach ($users as $user) {
            if ($user->getUsername() == $utente->getUsername()) {
                $user->checkPassword($utente->getPassword());

                $utente->getDataLost();

                // Inserimento nuova sessione per l'utente che ha effettuato il login
                $stmt = $conn->prepare("INSERT INTO sessioni (id_sessione, utente, data_login, data_logout) VALUES (:id_sessione, :utente, NOW(), NULL)");
                $stmt->bindParam(':id_sessione', session_id());
                $stmt->bindParam(':utente', $user->getId());
                $stmt->execute();

                // ID del record della sessione appena creata e ID del profilo dell'utente appena registrato
                $_SESSION["idSessione"] = session_id();
                $_SESSION["idProfilo"] = $user->getProfilo();

                return true;
            }
        }
        return false;
    }

    function logout() {
        global $conn;
        if (isset($_SESSION["idSessione"])) {
            $query = $conn->prepare("UPDATE sessioni SET data_logout = NOW() WHERE id_sessione = :idSessione AND data_logout IS NULL LIMIT 1");
            $query->bindParam(':idSessione', $_SESSION["idSessione"]);
            $query->execute();

            session_unset();
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

    function usersDBtoClass($attivo = null) {
        global $conn;
        $u = [];

        if ($attivo === null) {
            $stmt = $conn->prepare("SELECT * FROM utenti");
        } else {
            $stmt = $conn->prepare("SELECT * FROM utenti WHERE attivo = :attivo");
            $stmt->bindParam(':attivo', $attivo);
        }

        try {
            $stmt->execute();
            $utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($utenti as $utente) {
                $u[] = new UserObj($utente);
            }
        } catch (PDOException $e) {
            $u = [];
        }

        return $u;
    }

    function sessionDBtoClass($limit = null) {
        global $conn;
        $s = [];

        if ($limit != null) {
            $stmt = $conn->prepare("SELECT sessioni.id id, sessioni.id_sessione id_sessione, sessioni.data_login data_login, sessioni.data_logout data_logout, utenti.username username FROM sessioni JOIN utenti ON sessioni.utente = utenti.id ORDER BY sessioni.data_login DESC LIMIT :limit");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        } else {
            $stmt = $conn->prepare("SELECT sessioni.id id, sessioni.id_sessione id_sessione, sessioni.data_login data_login, sessioni.data_logout data_logout, utenti.username username FROM sessioni JOIN utenti ON sessioni.utente = utenti.id ORDER BY sessioni.data_login DESC");
        }

        try {
            $stmt->execute();
            $sessioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($sessioni as $sessione) {
                $s[] = new Session($sessione);
            }
        } catch (PDOException $e) {
            $s = [];
        }

        return $s;
    }
?>