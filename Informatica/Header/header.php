<style>
h2 {
    height: 15vh;              /* imposta l'altezza al 15% della finestra del browser */
    margin: 0;                 /* rimuove il margine predefinito del tag h2 */
    background-color: #2c54f5ff; /* assegna il colore di sfondo all'header */
    display: flex;             /* attiva il layout Flexbox per gestire l'allineamento interno */
    align-items: center;       /* centra verticalmente il contenuto dentro l'h2 */
    justify-content: center;   /* centra orizzontalmente il contenuto */
    position: relative;        /* crea un riferimento per elementi posizionati in modo assoluto */
}

h2 form {
    position: absolute;        /* posiziona il form indipendentemente dal flusso normale */
    right: 20px;               /* sposta il form a 20px dal bordo destro dell'h2 */
    margin: 0;                 /* elimina eventuali margini predefiniti del form */
}

h2 button {
    padding: 6px 12px;         /* aggiunge spazio interno per rendere il bottone più leggibile */
    cursor: pointer;           /* cambia il cursore in "mano" quando si passa sopra il bottone */
}
</style>


<h2>
    Sistema Login PHP

    <?php
    if (isset($_SESSION['utente'])) {

        echo " - Benvenuto: " . htmlspecialchars($_SESSION['nome']) . " " . htmlspecialchars($_SESSION['cognome']);

        echo '
            <form method="POST" action="area_utente.php">
                <button type="submit" name="logout">Logout</button>
            </form>
        ';
    }
    ?>
</h2>
