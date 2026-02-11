<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Anagrafica</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <form action="fine.php" method="post">
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" required>
            <label for="cognome">Cognome</label>
            <input type="text" name="cognome" id="cognome" required>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            <label for="telefono">Telefono</label>
            <input type="text" name="telefono" id="telefono" required>

            <hr>

            <div class="check">
                <input type="checkbox" name="active_hobbies" id="active_hobbies">
                <label for="active_hobbies">Hobbie</label>
            </div>

            <label for="hobbies">Inserisci gli Hobbies</label>
            <textarea name="hobbies" id="hobbies" rows="5" cols="30" disabled></textarea>

            <div class="check">
                <input type="checkbox" name="active_sports" id="active_sports">
                <label for="active_sports">Sport</label>
            </div>

            <label for="sports">Inserisci gli Sport</label>
            <textarea name="sports" id="sports" rows="5" cols="30" disabled></textarea>

            <hr>

            <input type="submit" value="Invia">
        </form>

        <script src="script.js"></script>
    </body>
</html>