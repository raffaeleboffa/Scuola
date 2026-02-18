<?php
session_start();  // Avvia o riprende la sessione PHP per poter usare $_SESSION

/* LOGOUT */
if (isset($_POST['logout'])) {  
    // Controlla se è stato premuto il bottone logout (metodo POST)

    session_destroy();  
    // Distrugge completamente la sessione

    setcookie("utente", "", time() - 3600);  
    // Elimina il cookie impostando una scadenza passata

    header("Location: home.php");  
    // Reindirizza l'utente alla pagina principale

    exit();  
    // Interrompe l'esecuzione dello script
}

/* Condizione accesso */
if (!isset($_SESSION['utente'])) {  
    // Verifica se l'utente NON è autenticato

    header("Location: home.php");  
    // Se non è loggato, lo rimanda alla home

    exit();  
    // Interrompe l'esecuzione
}
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Area Utente</title>

<style>
body {
    margin: 0; /* Elimina il margine predefinito del browser */

    font-family: Arial; /* Imposta il carattere del testo */

    min-height: 100vh; /* Altezza minima pari al 100% della finestra del browser */

    display: flex;  /* Attiva il layout Flexbox per gestire la disposizione degli elementi */

    flex-direction: column; /* Dispone gli elementi del body in verticale (uno sotto l'altro) */
}

/* Contenuto centrato */
h3, p {
    text-align: center; /* Centra orizzontalmente il testo dei tag h3 e p */
}

/* Card dati utente */
.dati-utente {
    width: 500px;
    margin: 30px auto;
    padding: 25px;
    background-color: #f9f9f9;
    border: 2px solid #2c54f5;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.dati-utente h4 {
    margin-top: 0;
    color: #2c54f5;
    text-align: center;
    font-size: 22px;
    border-bottom: 2px solid #2c54f5;
    padding-bottom: 10px;
}

.dati-utente table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.dati-utente td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

.dati-utente td:first-child {
    font-weight: bold;
    color: #2c54f5;
    width: 40%;
}

.dati-utente td:last-child {
    color: #333;
}

.dati-utente tr:last-child td {
    border-bottom: none;
}

/* Icone */
.icona {
    margin-right: 8px;
}
</style>


</head>
<body>

<?php include("header.php"); ?>

<h3>Area Utente</h3>

<p>Accesso effettuato correttamente.</p>

<!-- Card con i dati dell'utente -->
<div class="dati-utente">
    <h4>📋 I Tuoi Dati</h4>
    <table>
        <tr>
            <td><span class="icona">👤</span>Nome Utente:</td>
            <td><?php echo htmlspecialchars($_SESSION['utente']); ?></td>
        </tr>
        <tr>
            <td><span class="icona">✏️</span>Nome:</td>
            <td><?php echo htmlspecialchars($_SESSION['nome']); ?></td>
        </tr>
        <tr>
            <td><span class="icona">✏️</span>Cognome:</td>
            <td><?php echo htmlspecialchars($_SESSION['cognome']); ?></td>
        </tr>
        <tr>
            <td><span class="icona">🏠</span>Indirizzo:</td>
            <td><?php echo htmlspecialchars($_SESSION['indirizzo']); ?></td>
        </tr>
        <tr>
            <td><span class="icona">🌆</span>Città:</td>
            <td><?php echo htmlspecialchars($_SESSION['citta']); ?></td>
        </tr>
    </table>
</div>

<?php include("footer.php"); ?>

</body>
</html>
