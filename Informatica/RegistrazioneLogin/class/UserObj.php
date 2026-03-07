<?php
    class UserObj {
        private $id;
        private $nome;
        private $cognome;
        private $username;
        private $email;
        private $password;
        private $telefono;
        private $indirizzo;
        private $CAP;
        private $citta;
        private $profilo;
        private $attivo;

        public function __construct($id, $nome, $cognome, $username, $email, $password, $telefono, $indirizzo, $CAP, $citta, $profilo) {
            $this->id = $id;
            $this->nome = $nome;
            $this->cognome = $cognome;
            $this->username = $username;
            $this->email = $email;
            $this->password = $password;
            $this->telefono = $telefono;
            $this->indirizzo = $indirizzo;
            $this->CAP = $CAP;
            $this->citta = $citta;
            $this->profilo = $profilo;
            $this->attivo = 1;
        }

        public function putInDB() {
            global $conn;
            $stmt = $conn->prepare("INSERT INTO utenti (nome, cognome, username, email, password, telefono, indirizzo, CAP, citta, profilo, attivo) VALUES (:nome, :cognome, :username, :email, :password, :telefono, :indirizzo, :CAP, :citta, :profilo, 1)");
            $stmt->bindParam(':nome', $this->nome);
            $stmt->bindParam(':cognome', $this->cognome);
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', password_hash($this->password, PASSWORD_DEFAULT));
            $stmt->bindParam(':telefono', $this->telefono);
            $stmt->bindParam(':indirizzo', $this->indirizzo);
            $stmt->bindParam(':CAP', $this->CAP);
            $stmt->bindParam(':citta', $this->citta);
            $stmt->bindParam(':profilo', $this->profilo);

            try {
                return $stmt->execute();
            } catch (PDOException $e) {
                return false;
            }
        }

        public function getUsername() {
            return $this->username;
        }

        public function getPassword() {
            return $this->password;
        }

        public function getId() {
            if ($this->id) {
                return $this->id;
            } else {
                global $conn;
                $stmt = $conn->prepare("SELECT id FROM utenti WHERE username = :username");
                $stmt->bindParam(':username', $this->username);

                try {
                    $stmt->execute();
                    $utente = $stmt->fetch(PDO::FETCH_ASSOC);

                    $this->id = $utente['id'];
                    return $utente['id'];
                } catch (PDOException $e) {
                    return null;
                }
            }
        }

        public function checkPassword($password) {
            return password_verify($password, $this->password);
        }
    }