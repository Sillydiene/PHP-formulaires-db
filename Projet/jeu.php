<?php
session_start();
//si on se deconnecte on ne peut plus rentrer dans jeu.php tant que l'utilisateur n'est pas reconnecté
if (!isset($_SESSION['user'])) {
    // Rediriger vers la page de connexion
    die("Vous devez être connecté pour accéder à ce jeu. <a href='connexion.php'>Cliquez ici pour vous connecter.</a>");

}
$nom_utilisateur = $_SESSION['user'];

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>DeviNet - Jeu de devinettes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #d9d487;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: black;
            margin-top: 20px;
        }

        a {
            color: blue;
            text-decoration: none;
        }

        .container {
            background-color: white;
            border: 1px solid #ccc;
            width: 400px;
            margin: 30px auto;
            padding: 20px;
            box-shadow: 0 0 10px #ccc;
        }

        .title-red {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .form-section {
            border-top: 1px solid #ccc;
            padding-top: 15px;
        }

        select {
            margin: 5px;
            padding: 5px;
        }

        button {
            margin: 10px;
            padding: 8px 16px;
            font-weight: bold;
        }

        footer {
            background-color: #e0e08a;
            padding: 10px;
            font-size: 14px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <h1><strong>DeviNet</strong> <a href="#">Jeu de devinettes</a></h1>
    <h2>Bonjour, <?php echo htmlspecialchars($nom_utilisateur); ?> !</h2>
    <div class="container">
        <div class="title-red">Jeu de devinettes</div>
        <div class="form-section">
            <form method="POST" >
                <p><strong>Formulaire de jeu</strong></p>
                <p>Le système va générer 5 nombres allant de 0 à 12.<br>
                    Sélectionnez des nombres ci-dessous pour deviner ces nombres à l'avance!</p>


                <select name="selection0">
                    <!-- Options de 0 à 12 -->
                    <script>
                        for (let i = 0; i <= 12; i++) {
                            document.write(`<option value="${i}">${i}</option>`);
                        }
                    </script>
                </select>
                <select name="selection1">
                    <script>
                        for (let i = 0; i <= 12; i++) {
                            document.write(`<option value="${i}">${i}</option>`);
                        }
                    </script>
                </select>
                <select name="selection2">
                    <script>
                        for (let i = 0; i <= 12; i++) {
                            document.write(`<option value="${i}">${i}</option>`);
                        }
                    </script>
                </select>
                <select name="selection3">
                    <script>
                        for (let i = 0; i <= 12; i++) {
                            document.write(`<option value="${i}">${i}</option>`);
                        }
                    </script>
                </select>
                <select name="selection4">
                    <script>
                        for (let i = 0; i <= 12; i++) {
                            document.write(`<option value="${i}">${i}</option>`);
                        }
                    </script>
                </select>

                <br>
                <button type="submit" name="selections">JOUER</button>
                <button type="submit" name="logout">SE DÉCONNECTER</button>
            </form>
        </div>
    </div>

    <footer>©2025 Tous droits réservés.</footer>
</body>

</html>
<?php


// traitement de deconnexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: connexion.php");
    exit();
}

//traitenement du jeu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selections'])) {
    function genererNombresAleatoires() {
        $nombresAleatoires = [];
        while (count($nombresAleatoires) < 5) {
            // Générer un nombre aléatoire entre 0 et 12
            $nombre = rand(0, 12);
            if (!in_array($nombre, $nombresAleatoires)) {
                $nombresAleatoires[] = $nombre;
            }
        }
        return $nombresAleatoires;
    }

    function verifierSelections($selections, $nombresAleatoires) {
        $correctCount = 0;
        foreach ($selections as $selection) {
            if (in_array($selection, $nombresAleatoires)) {
                $correctCount++;
            }
        }
        return $correctCount;
    }

    function afficherResultats($correctCount, $nombresAleatoires, $selections) {
        echo "<h2>Résultats du jeu</h2>";
        echo "<p>Vous avez deviné <strong>$correctCount</strong> nombres correctement.</p>";
        echo "<p>Nombres générés : " . implode(", ", $nombresAleatoires) . "</p>";
        echo "<p>Les nombres que vous avez soumis sont : " . implode(", ", $selections) . "</p>";

        if ($correctCount === 5) {
            echo "<p>Vous êtes un EXCELLENT devin !</p>";
        } elseif ($correctCount >= 3) {
            echo "<p>Vous êtes un BON devin !</p>";
        } elseif ($correctCount >= 1) {
            echo "<p>Pas mal ! Réessayez pour faire mieux.</p>";
        } else {
            echo "<p>Aucun chiffre deviné. Réessayez !</p>";
        }
    }


    $selections = [];
    for ($i = 0; $i < 5; $i++) {
        if (isset($_POST["selection$i"])) {
            $selections[] = intval($_POST["selection$i"]);
        }
    }

    if (count(array_filter($selections)) === 0) {
        echo "<p style='color: red;'>Vous n'avez pas joué.</p>";
        exit();
    }

    if (count($selections) !== 5 || count(array_unique($selections)) !== 5) {
        echo "<p style='color: red;'>Veuillez sélectionner 5 nombres distincts.</p>";
        exit();
    }


    $nombresAleatoires = genererNombresAleatoires();
    $correctCount = verifierSelections($selections, $nombresAleatoires);
    afficherResultats($correctCount, $nombresAleatoires, $selections);

    exit();
}

