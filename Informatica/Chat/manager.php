<?php
    session_start();

    require_once 'database/config.php';

    function sendMail($to, $subject, $message) {
            $to = "somebody@example.com";
            $subject = "My subject";
            $txt = "Hello world!";
            $headers = "From: webmaster@example.com" . "\r\n";
            if (mail($to,$subject,$txt,$headers)) {
                echo "Email inviata con successo.";
            } else {
                echo "Errore nell'invio dell'email.";
            }