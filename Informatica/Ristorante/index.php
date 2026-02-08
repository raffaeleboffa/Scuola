<?php
    $piatti = array(
        'antipasti' => array("Bruschette al pomodoro", "Tagliere di salumi", "Crostini ai fegatini", "Caprese di bufala", "Fiori di zucca fritti", "Carpaccio di manzo", "Insalata di mare", "Polpette di melanzane", "Tartare di salmone", "Olive ascolane"),
        'formaggi' => array("Pecorino Toscano", "Gorgonzola DOP", "Taleggio", "Fontina Valdostana", "Parmigiano Reggiano 24 mesi", "Asiago Pressato", "Burrata pugliese", "Caciocavallo Silano", "Toma Piemontese", "Mozzarella di Bufala Campana"),
        'primi' => array("Ravioli ricotta e spinaci", "Spaghetti alla carbonara", "Lasagne alla bolognese", "Penne all'arrabbiata", "Risotto ai funghi porcini", "Trofie al pesto", "Tagliatelle al ragù di cinghiale", "Gnocchi ai quattro formaggi", "Tortellini in brodo", "Orecchiette alle cime di rapa"),
        'secondi' => array("Bistecca alla fiorentina", "Saltimbocca alla romana", "Pollo alla cacciatora", "Branzino al sale", "Tagliata di manzo al rosmarino", "Ossobuco alla milanese", "Costolette d'agnello scottadito", "Merluzzo alla livornese", "Scaloppine al limone", "Polpette al sugo"),
        'contorni' => array("Patate al forno", "Verdure grigliate", "Insalata mista", "Spinaci al burro", "Cicoria ripassata", "Peperonata", "Fagioli all'uccelletto", "Caponata siciliana", "Friarielli in padella", "Purea di patate"),
        'dessert' => array("Tiramisù della casa", "Panna cotta ai frutti di bosco", "Crostata di marmellata", "Salame al cioccolato", "Cantucci e vin santo", "Zuppa Inglese", "Cannoli siciliani", "Torta della nonna", "Semifreddo al pistacchio", "Mousse al cioccolato"),
        'frutta' => array("Ananas fresco", "Fragole con panna", "Macedonia di stagione", "Melone", "Pesca sciroppata", "Pere al vino rosso", "Carpaccio d'arance", "Anguria", "Sorbetto al limone", "Spiedini di frutta mista"),
        'cafeAmari' => array("Caffè Espresso", "Caffè Macchiato", "Caffè Corretto", "Limoncello", "Mirto di Sardegna", "Amaro del Capo", "Grappa barricata", "Sambuca", "Montenegro", "Fernet Branca")
    );

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $selected = array(
            'antipasti' => $_POST['antipasti'],
            'formaggi' => $_POST['formaggi'],
            'primi' => $_POST['primi'],
            'secondi' => $_POST['secondi'],
            'contorni' => $_POST['contorni'],
            'dessert' => $_POST['dessert'],
            'frutta' => $_POST['frutta'],
            'cafeAmari' => $_POST['cafeAmari']
        );

        $counter = 0;
        foreach ($selected as $category => $dish) {
            if ($dish != "none") {
                $counter++;
            }
        }

        if ($counter < 6) {
            echo "<script>alert('Per favore, seleziona almeno 6 piatti.');</script>";
        } else {
            echo "<script>alert('Ordine inviato con successo!');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Osteria del Borgo</title>
        <link rel="stylesheet" href="style.css">
        <script src="script.js"></script>
    </head>
    <body>
        <form action="" method="post">
            <div class="menu">
                <p class="btn_menu" onclick="openSez('antipasti')">Antipasti</p>
                <p class="btn_menu" onclick="openSez('formaggi')">Formaggi</p>
                <p class="btn_menu" onclick="openSez('primi')">Primi</p>
                <p class="btn_menu" onclick="openSez('secondi')">Secondi</p>
                <p class="btn_menu" onclick="openSez('contorni')">Contorni</p>
                <p class="btn_menu" onclick="openSez('dessert')">Dessert</p>
                <p class="btn_menu" onclick="openSez('frutta')">Frutta</p>
                <p class="btn_menu" onclick="openSez('cafeAmari')">Cafè & Amari</p>
            </div>
            <div class="main">
                <header>
                    <h1>Osteria del Borgo</h1>
                    <p>Un'esperienza sensoriale unica</p>
                </header>
                <div class="sez" id="antipasti">
                    <h2>Antipasti</h2>
                    <select name="antipasti" id="antipasti_select">
                        <option value="none">Scegli un piatto</option>
                        <?php foreach($piatti['antipasti'] as $p) echo "<option value='$p'>$p</option>"; ?>
                    </select>
                </div>
                <div class="sez" id="formaggi">
                    <h2>Formaggi</h2>
                    <select name="formaggi" id="formaggi_select">
                        <option value="none">Scegli un piatto</option>
                        <?php foreach($piatti['formaggi'] as $p) echo "<option value='$p'>$p</option>"; ?>
                    </select>
                </div>
                <div class="sez" id="primi">
                    <h2>Primi</h2>
                    <select name="primi" id="primi_select">
                        <option value="none">Scegli un piatto</option>
                        <?php foreach($piatti['primi'] as $p) echo "<option value='$p'>$p</option>"; ?>
                    </select>
                </div>
                <div class="sez" id="secondi">
                    <h2>Secondi</h2>
                    <select name="secondi" id="secondi_select">
                        <option value="none">Scegli un piatto</option>
                        <?php foreach($piatti['secondi'] as $p) echo "<option value='$p'>$p</option>"; ?>
                    </select>
                </div>
                <div class="sez" id="contorni">
                    <h2>Contorni</h2>
                    <select name="contorni" id="contorni_select">
                        <option value="none">Scegli un piatto</option>
                        <?php foreach($piatti['contorni'] as $p) echo "<option value='$p'>$p</option>"; ?>
                    </select>
                </div>
                <div class="sez" id="dessert">
                    <h2>Dessert</h2>
                    <select name="dessert" id="dessert_select">
                        <option value="none">Scegli un piatto</option>
                        <?php foreach($piatti['dessert'] as $p) echo "<option value='$p'>$p</option>"; ?>
                    </select>
                </div>
                <div class="sez" id="frutta">
                    <h2>Frutta</h2>
                    <select name="frutta" id="frutta_select">
                        <option value="none">Scegli un piatto</option>
                        <?php foreach($piatti['frutta'] as $p) echo "<option value='$p'>$p</option>"; ?>
                    </select>
                </div>
                <div class="sez" id="cafeAmari">
                    <h2>Cafè & Amari</h2>
                    <select name="cafeAmari" id="cafeAmari_select">
                        <option value="none">Scegli un piatto</option>
                        <?php foreach($piatti['cafeAmari'] as $p) echo "<option value='$p'>$p</option>"; ?>
                    </select>
                </div>
                <div class="invia">
                    <input type="submit" value="Invia Ordine">
                </div>
            </div>
        </form>
    </body>
</html>