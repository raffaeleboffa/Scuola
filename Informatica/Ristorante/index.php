<?php
    include 'menu.php';
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
        <form action="final.php" method="post">
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
                        <?php 
                            foreach($piatti['antipasti'] as $p => $pr) {
                                echo "<option value='$p' $sel>$p</option>"; 
                            }
                        ?>
                    </select>
                </div>
                <div class="sez" id="formaggi">
                    <h2>Formaggi</h2>
                    <select name="formaggi" id="formaggi_select">
                        <option value="none">Scegli un piatto</option>
                        <?php 
                            foreach($piatti['formaggi'] as $p => $pr) {
                                echo "<option value='$p' $sel>$p</option>"; 
                            }
                        ?>
                    </select>
                </div>
                <div class="sez" id="primi">
                    <h2>Primi</h2>
                    <select name="primi" id="primi_select">
                        <option value="none">Scegli un piatto</option>
                        <?php 
                            foreach($piatti['primi'] as $p => $pr) {
                                echo "<option value='$p' $sel>$p</option>"; 
                            }
                        ?>
                    </select>
                </div>
                <div class="sez" id="secondi">
                    <h2>Secondi</h2>
                    <select name="secondi" id="secondi_select">
                        <option value="none">Scegli un piatto</option>
                        <?php 
                            foreach($piatti['secondi'] as $p => $pr) {
                                echo "<option value='$p' $sel>$p</option>"; 
                            }
                        ?>
                    </select>
                </div>
                <div class="sez" id="contorni">
                    <h2>Contorni</h2>
                    <select name="contorni" id="contorni_select">
                        <option value="none">Scegli un piatto</option>
                        <?php 
                            foreach($piatti['contorni'] as $p => $pr) {
                                echo "<option value='$p' $sel>$p</option>"; 
                            }
                        ?>
                    </select>
                </div>
                <div class="sez" id="dessert">
                    <h2>Dessert</h2>
                    <select name="dessert" id="dessert_select">
                        <option value="none">Scegli un piatto</option>
                        <?php 
                            foreach($piatti['dessert'] as $p => $pr) {
                                echo "<option value='$p' $sel>$p</option>"; 
                            }
                        ?>
                    </select>
                </div>
                <div class="sez" id="frutta">
                    <h2>Frutta</h2>
                    <select name="frutta" id="frutta_select">
                        <option value="none">Scegli un piatto</option>
                        <?php 
                            foreach($piatti['frutta'] as $p => $pr) {
                                echo "<option value='$p' $sel>$p</option>"; 
                            }
                        ?>
                    </select>
                </div>
                <div class="sez" id="cafeAmari">
                    <h2>Cafè & Amari</h2>
                    <select name="cafeAmari" id="cafeAmari_select">
                        <option value="none">Scegli un piatto</option>
                        <?php 
                            foreach($piatti['cafeAmari'] as $p => $pr) {
                                echo "<option value='$p' $sel>$p</option>"; 
                            }
                        ?>
                    </select>
                </div>
                <div class="invia">
                    <input type="submit" value="Invia Ordine">
                </div>
            </div>
        </form>
    </body>
</html> 