<?php
    $connection = null;

    function loadEnv() {
        $envFile = __DIR__ . '/.env';

        if (file_exists($envFile)) {

            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                list($key, $value) = explode('=', $line, 2);
                putenv("$key=$value");
            }

        } else {
            echo "File di configurazione database non trovato:" . $envFile;
        }
    }

    function initializeDB() {
        $dbms = getenv('DBMS');

        switch ($dbms) {
            case 'mysql': {
                $host = getenv('DB_HOST');
                $username = getenv('DB_USERNAME');
                $password = getenv('DB_PASSWORD');
                $dbname = getenv('DB_NAME');

                $dsn = "$dbms:host=$host;dbname=$dbname;charset=utf8mb4";

                try {
                    return new PDO($dsn, $username, $password);
                } catch (PDOException $e) {
                    echo "Connessione al database fallita.";
                }
                break;
            }

            case 'sqlite': {
                $dbname = getenv('DB_FILE_PATH');
                
                $dsn = "$dbms:$dbname";

                try {
                    return new PDO($dsn);
                } catch (PDOException $e) {
                    echo "Connessione al database fallita.";
                }
                break;
            }

            default:{
                echo "DBMS non supportato:" . $dbms;
                exit;
            }
        }
    }

    function getDBconnection() {
        global $connection;

        if ($connection === null) {
            loadEnv();
            $connection = initializeDB();
        }

        return $connection;
    }

    function prepareQuery($query, $values = []) {
        try {
            $conn = getDBconnection();
            $stmt = $conn->prepare($query);
            $stmt->execute($values);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Errore nella preparazione della query.";
            return [];
        }
    }