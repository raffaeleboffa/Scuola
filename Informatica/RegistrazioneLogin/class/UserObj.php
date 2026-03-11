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

        private $stato;

        public function __construct($values) {
            $this->id = isset($values['id']) ? $values['id'] : null;
            $this->nome = isset($values['nome']) ? $values['nome'] : null;
            $this->cognome = isset($values['cognome']) ? $values['cognome'] : null;
            $this->username = isset($values['username']) ? $values['username'] : null;
            $this->email = isset($values['email']) ? $values['email'] : null;
            $this->password = isset($values['password']) ? $values['password'] : null;
            $this->telefono = isset($values['telefono']) ? $values['telefono'] : null;
            $this->indirizzo = isset($values['indirizzo']) ? $values['indirizzo'] : null;
            $this->CAP = isset($values['CAP']) ? $values['CAP'] : null;
            $this->citta = isset($values['citta']) ? $values['citta'] : null;
            $this->profilo = isset($values['profilo']) ? $values['profilo'] : null;
            $this->attivo = isset($values['attivo']) ? $values['attivo'] : 1;

            $this->stato = null;
        }

        // Getter e Setter

            // ID
            public function getId() {
                return $this->id;
            }

            // Nome
            public function getNome() {
                return $this->nome;
            }
            public function setNome($nome) {
                $this->nome = $nome;
                $this->stato = "modificato";
            }

            // Cognome
            public function getCognome() {
                return $this->cognome;
            }
            public function setCognome($cognome) {
                $this->cognome = $cognome;
                $this->stato = "modificato";
            }

            // Username
            public function getUsername() {
                return $this->username;
            }

            // Email
            public function getEmail() {
                return $this->email;
            }
            public function setEmail($email) {
                $this->email = $email;
                $this->stato = "modificato";
            }

            // Password
            public function getPassword() {
                return $this->password;
            }

            // Telefono
            public function getTelefono() {
                return $this->telefono;
            }
            public function setTelefono($telefono) {
                $this->telefono = $telefono;
                $this->stato = "modificato";
            }

            // Indirizzo
            public function getIndirizzo() {
                return $this->indirizzo;
            }
            public function setIndirizzo($indirizzo) {
                $this->indirizzo = $indirizzo;
                $this->stato = "modificato";
            }

            // CAP
            public function getCAP() {
                return $this->CAP;
            }
            public function setCAP($CAP) {
                $this->CAP = $CAP;
                $this->stato = "modificato";
            }

            // Città
            public function getCitta() {
                return $this->citta;
            }
            public function setCitta($citta) {
                $this->citta = $citta;
                $this->stato = "modificato";
            }

            // Profilo
            public function getProfilo() {
                return $this->profilo;
            }
            public function setProfilo($profilo) {
                $this->profilo = $profilo;
                $this->stato = "modificato";
            }

             // Attivo
            public function getAttivo() {
                return $this->attivo;
            }
            public function setAttivo($attivo) {
                $this->attivo = $attivo;
                $this->stato = "modificato";
            }

            // Stato
            public function getStato() {
                return $this->stato;
            }

        // Funzioni di gestione

        public function getDataLost() {
            global $conn;
            $stmt = $conn->prepare("SELECT * FROM utenti WHERE username = :username");
            $stmt->bindParam(':username', $this->username);

            try {
                $stmt->execute();
                $utente = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->id = isset($utente['id']) ? $utente['id'] : null;
                $this->nome = isset($utente['nome']) ? $utente['nome'] : null;
                $this->cognome = isset($utente['cognome']) ? $utente['cognome'] : null;
                $this->email = isset($utente['email']) ? $utente['email'] : null;
                $this->telefono = isset($utente['telefono']) ? $utente['telefono'] : null;
                $this->indirizzo = isset($utente['indirizzo']) ? $utente['indirizzo'] : null;
                $this->CAP = isset($utente['CAP']) ? $utente['CAP'] : null;
                $this->citta = isset($utente['citta']) ? $utente['citta'] : null;
                $this->profilo = isset($utente['profilo']) ? $utente['profilo'] : null;
                $this->attivo = isset($utente['attivo']) ? $utente['attivo'] : 1;
            } catch (PDOException $e) {
                return null;
            }
        }

        public function deleteUser() {
            $this->stato = "cancellato";
        }

        public function checkPassword($password) {
            return password_verify($password, $this->password);
        }

        // CRUD database

        public function addRecord() {
            global $conn;
            $stmt = $conn->prepare("INSERT INTO utenti (nome, cognome, username, email, password, telefono, indirizzo, CAP, citta, profilo, attivo) VALUES (:nome, :cognome, :username, :email, :password, :telefono, :indirizzo, :CAP, :citta, :profilo, :attivo)");
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
            $stmt->bindParam(':attivo', $this->attivo);

            try {
                return $stmt->execute();
            } catch (PDOException $e) {
                return false;
            }
        }

        public function delRecord() {
            if ($this->id != null) {
                global $conn;
                $stmt = $conn->prepare("DELETE FROM utenti WHERE id = :id");
                $stmt->bindParam(':id', $this->id);

                try {
                    return $stmt->execute();
                } catch (PDOException $e) {
                    return false;
                }
            } else {
                return false;
            }
        }

        public function modRecord() {
            if ($this->id != null) {
                global $conn;
                $stmt = $conn->prepare("UPDATE utenti SET nome = :nome, cognome = :cognome, email = :email, telefono = :telefono, indirizzo = :indirizzo, CAP = :CAP, citta = :citta, profilo = :profilo, attivo = :attivo WHERE id = :id");
                $stmt->bindParam(':nome', $this->nome);
                $stmt->bindParam(':cognome', $this->cognome);
                $stmt->bindParam(':email', $this->email);
                $stmt->bindParam(':telefono', $this->telefono);
                $stmt->bindParam(':indirizzo', $this->indirizzo);
                $stmt->bindParam(':CAP', $this->CAP);
                $stmt->bindParam(':citta', $this->citta);
                $stmt->bindParam(':profilo', $this->profilo);
                $stmt->bindParam(':attivo', $this->attivo);
                $stmt->bindParam(':id', $this->id);

                try {
                    $this->stato = null;
                    return $stmt->execute();
                } catch (PDOException $e) {
                    return false;
                }
            } else {
                return false;
            }
        }
    }