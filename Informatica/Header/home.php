<?php
session_start(); 
// Avvia o riprende una sessione PHP.
// Serve per poter usare la variabile globale $_SESSION.

/* ARRAY UTENTI */
// Array associativo con i dati degli utenti registrati
$utenti = [
    [
        'utente' => 'mario.rossi',
        'password' => 'password123',
        'nome' => 'Mario',
        'cognome' => 'Rossi',
        'indirizzo' => 'Via Roma 10',
        'citta' => 'Milano'
    ],
    [
        'utente' => 'laura.bianchi',
        'password' => 'laura2024',
        'nome' => 'Laura',
        'cognome' => 'Bianchi',
        'indirizzo' => 'Corso Italia 25',
        'citta' => 'Roma'
    ],
    [
        'utente' => 'giuseppe.verdi',
        'password' => 'verdi456',
        'nome' => 'Giuseppe',
        'cognome' => 'Verdi',
        'indirizzo' => 'Piazza Garibaldi 5',
        'citta' => 'Napoli'
    ]
];

/* Se già loggato */
if (isset($_SESSION['utente'])) {
    // Controlla se esiste la variabile di sessione 'utente'.
    // Se esiste significa che l'utente è già autenticato.

    header("Location: area_utente.php");
    // Reindirizza l'utente alla pagina dell'area riservata.

    exit();
    // Interrompe l'esecuzione dello script per evitare che venga eseguito altro codice.
}

/* Variabile per messaggi di errore */
$errore = "";

/* LOGIN */
if (isset($_POST['utente']) && isset($_POST['password'])) {
    // Verifica se il form è stato inviato
    // controllando se esistono i campi utente e password nel metodo POST.

    $utente_inserito = $_POST['utente'];
    $password_inserita = $_POST['password'];
    
    // Variabile per verificare se il login è valido
    $login_valido = false;
    
    // Ciclo per controllare se le credenziali corrispondono a un utente nell'array
    foreach ($utenti as $utente_dati) {
        if ($utente_dati['utente'] === $utente_inserito && 
            $utente_dati['password'] === $password_inserita) {
            
            // Login valido: salva tutti i dati dell'utente nella sessione
            $_SESSION['utente'] = $utente_dati['utente'];
            $_SESSION['nome'] = $utente_dati['nome'];
            $_SESSION['cognome'] = $utente_dati['cognome'];
            $_SESSION['indirizzo'] = $utente_dati['indirizzo'];
            $_SESSION['citta'] = $utente_dati['citta'];
            
            // Crea un cookie con il nome utente
            setcookie("utente", $_SESSION['utente'], time() + (7 * 24 * 60 * 60));
            
            $login_valido = true;
            break; // Esce dal ciclo
        }
    }
    
    if ($login_valido) {
        // Reindirizza all'area utente
        header("Location: area_utente.php");
        exit();
    } else {
        // Credenziali errate
        $errore = "Nome utente o password non corretti!";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Esercitazione Login</title>

<style>
body {
    margin: 0; /* elimina margine predefinito */
    font-family: Arial; /* imposta il font */
    min-height: 100vh; /* altezza minima = 100% finestra */
    display: flex; /* attiva layout flessibile */
    flex-direction: column; /* disposizione verticale */
}

/* Titolo Login */
h3 {
    margin: 10px; /* spazio esterno intorno */
    text-align: center; /* centra il testo */
}

/* Form centrato */
form {
    width: 280px; /* larghezza fissa del form */
    margin: 10px auto 0 auto; /* centra orizzontalmente */
    padding: 20px; /* spazio interno */
    border: 1px solid #ccc; /* bordo grigio sottile */
    border-radius: 8px; /* angoli arrotondati */
    background-color: #f9f9f9; /* colore sfondo chiaro */
    box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* ombra leggera */
}

/* Messaggio di errore */
.errore {
    color: red;
    text-align: center;
    margin: 10px 0;
    font-weight: bold;
}

/* Info utenti */
.info-utenti {
    width: 600px;
    margin: 20px auto;
    padding: 15px;
    background-color: #e8f4f8;
    border: 1px solid #b3d9e6;
    border-radius: 8px;
}

.info-utenti h4 {
    margin-top: 0;
    color: #2c54f5;
}

.info-utenti ul {
    list-style: none;
    padding: 0;
}

.info-utenti li {
    margin: 8px 0;
    padding: 8px;
    background-color: white;
    border-radius: 4px;
}
</style>


</head>
<body>

<?php include("header.php"); ?>

<h3>Login</h3>

<?php if ($errore != ""): ?>
    <p class="errore"><?php echo $errore; ?></p>
<?php endif; ?>

<form method="POST" action="">

    Nome Utente:<br>
    <input type="text" name="utente" required><br><br>

    Password:<br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Accedi</button>
</form>

<!-- Informazioni utenti di test -->
<div class="info-utenti">
    <h4>👤 Utenti di Test</h4>
    <ul>
        <li><strong>Utente:</strong> mario.rossi | <strong>Password:</strong> password123</li>
        <li><strong>Utente:</strong> laura.bianchi | <strong>Password:</strong> laura2024</li>
        <li><strong>Utente:</strong> giuseppe.verdi | <strong>Password:</strong> verdi456</li>
    </ul>
</div>

<?php include("footer.php"); ?>

</body>
</html>
