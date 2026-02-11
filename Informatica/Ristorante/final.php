<?php
    include 'menu.php';

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
        $tot = 0;
        $ordine = array();
        foreach ($selected as $category => $dish) {
            if ($dish != "none") {
                array_push($ordine, array("cat" => $category, "dish" => $dish, "price" => $piatti[$category][$dish]));
                $tot += $piatti[$category][$dish];
                $counter++;
            }
        }

        if ($counter < 6) {
            header('Location: index.php');
            exit();
        } else {
            echo "<script>alert('Ordine inviato con successo!');</script>";
        }
    } else {
        header('Location: index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Osteria del Borgo</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="ordine">
            <h2>Ordinazione</h2>
            <ul>
                <?php
                    foreach ($ordine as $i) { 
                        echo "<li><b>" . $i["cat"] . ":</b> " . $i["dish"] . " - " . $i["price"] . "€</li>";
                    }
                ?>
            </ul>
            <h2>Prezzo totale</h2>
            <p><?php echo $tot ?>€</p>
        </div>
    </body>
</html>