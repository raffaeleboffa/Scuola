<?php
    require_once 'database/config.php';

    function sendMail($to) {
        $subject = "Accesso a WEBLINK";
        $txt = "Ciao!\n\nPer accedere a WEBLINK, utilizza il seguente token privato:\n\n" . generaToken($to) . "\n\nSe ne avevi già uno, quest'ultimo verrà cambiato automaticamente.\n\nGrazie!";
        $headers = "From: raffaeleboffa92@gmail.com" . "\r\n";

        if (mail($to,$subject,$txt,$headers)) {
            return true;
        } else {
            return false;
        }
    }

    function generaToken($email) {
        $value = prepareQuery("SELECT id FROM utenti WHERE email = :email", [":email" => $email]);
        $words = prepareQuery("SELECT * FROM parole");

        if (empty($words)) {
            $token = bin2hex(random_bytes(10));
        } else {
            $token = $words[rand(0, count($words) - 1)]['parola'] . rand(1000, 9999) . $words[rand(0, count($words) - 1)]['parola'];
        }

        if ($value) {
            $userId = $value[0]['id'];
            
            prepareQuery("UPDATE utenti SET token = :token WHERE id = :id", [":token" => password_hash($token, PASSWORD_DEFAULT), ":id" => $userId]);
        } else {
            $lastId = prepareQuery("SELECT id FROM utenti ORDER BY id DESC LIMIT 1");
            $userId = !empty($lastId) ? $lastId[0]['id'] + 1 : 1;
            
            $username = "";

            do {
                $username = "utente" . bin2hex(random_bytes(6));
                $result = prepareQuery("SELECT * FROM utenti WHERE username = :username", [":username" => $username]);
            } while ($result);

            prepareQuery(
                "INSERT INTO utenti (nome, cognome, username, email, token, bio, profilo, data_creazione) VALUES (:nome, :cognome, :username, :email, :token, :bio, :profilo, NOW())",
                [
                    ":nome" => "Utente",
                    ":cognome" => $userId,
                    ":username" => $username,
                    ":email" => $email,
                    ":token" => password_hash($token, PASSWORD_DEFAULT),
                    ":bio" => null,
                    ":profilo" => "default.svg"
                ]
            );
        }

        return $userId . "#" . $token;
    }