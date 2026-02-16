<?php
    session_start();

    if(!isset($_SESSION["username"])){
        header("Location: index.php");
        exit();
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['logout'])){
            session_destroy();
            header("Location: index.php");
            exit();
        }
        
        if(isset($_POST['reset'])){
            setcookie("generi", "", time()-3600);
            header("Location: home.php");
            exit();
        }

        if(isset($_POST["genere"])){
            $genere = $_POST["genere"];
            $generi = array();
            if(isset($_COOKIE["generi"])){            
                $generi = unserialize($_COOKIE["generi"]);
                if($generi === false) $generi = array();
            }
            $generi[] = $genere;
            setcookie("generi", serialize($generi), time()+172800);
            
            // Reindirizza alla stessa pagina per evitare il reinvio del form (PRG Pattern)
            header("Location: home.php");
            exit();
        }
    }
    
    // Lettura dei generi (sia al primo caricamento che dopo il redirect)
    $generi = array();
    if(isset($_COOKIE["generi"])){
        $data = unserialize($_COOKIE["generi"]);
        if($data !== false) {
            $generi = $data;
        }
    }
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Catalogo Musicale</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="main">
            <div class="salvataggi">
                <ul>
                    <?php 
                        if(!empty($generi)){
                            foreach($generi as $g){
                                echo "<li>" . htmlspecialchars($g) . "</li>";
                            }
                        } else {
                            echo "<li>Nessun genere musicale aggiunto.</li>";
                        }
                    ?>
                </ul>
            </div>
            <div class="add">
                <form action="" method="post">
                    <input type="text" name="genere" placeholder="Aggiungi un genere musicale" required>
                    <input type="submit" value="Aggiungi">
                </form>
            </div>
            
            <div class="actions">
                <form action="" method="post">
                    <input type="hidden" name="reset" value="1">
                    <input type="submit" value="Reset Lista" class="btn-secondary">
                </form>
                
                <form action="" method="post">
                    <input type="hidden" name="logout" value="1">
                    <input type="submit" value="Logout" class="btn-danger">
                </form>
            </div>
        </div>
    </body>
</html>
