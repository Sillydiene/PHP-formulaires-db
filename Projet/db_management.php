<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
define('HOSTNAME', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', '');
define('DATABASE', 'jeu_devinette');


//Traitement AJAX XMLHttpRequest() (vérification du nom d'utilisateur)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['username'])) {
    $username = trim($_GET['username']);

    $mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
    if ($mysqli->connect_error) {
        echo "erreur";
        exit;
    }

    $stmt = $mysqli->prepare("SELECT id FROM compte_utilisateur WHERE nom_utilisateur = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "existe";
    } else {
        echo "disponible";
    }

    $stmt->close();
    $mysqli->close();
    exit;
}

// Traitement de l'inscription
if (isset($_POST['inscription'])) {
    try {
        function validerChamps($testusername, $testpassword, $testconfirmPassword)
        {
            if (empty($testusername) || empty($testpassword) || empty($testconfirmPassword)) {
                return "Veuillez remplir tous les champs.";
            }

            if ($testpassword !== $testconfirmPassword) {
                return "Les mots de passe ne correspondent pas.";
            }

            return "";
        }

        // Récupération des champs
        $testusername = trim($_POST['username'] ?? '');
        $testpassword = $_POST['password'] ?? '';
        $testconfirmPassword = $_POST['confirm'] ?? '';

        // Validation
        $erreur = validerChamps($testusername, $testpassword, $testconfirmPassword);
        if (!empty($erreur)) {
            header("Location: index.php?error=" . urlencode($erreur));
            exit;
        }
        $nom_utilisateur = $_POST['username'];
        $mot_de_passe = $_POST['password'];
        $confirm_mot_de_passe = $_POST['confirm'];

        // Connexion à la base de données
        $connection = new mysqli(HOSTNAME, USERNAME, PASSWORD);
        if (!file_exists("jeu_devinette.sql")) {
            throw new Exception("Le fichier jeu_devinette.sql est introuvable.");
        }

        $sql = file_get_contents("jeu_devinette.sql");
        $connection->multi_query($sql);
        while ($connection->more_results() && $connection->next_result()) {
            $connection->store_result();
        }

        $connection->select_db(DATABASE);
        $connection->query("DESC compte_utilisateur");
        if ($connection->error) {
            throw new Exception("La table compte_utilisateur n'existe pas.");
        }

        //si nom utilisateur existe deja
        $stmt = $connection->prepare("SELECT id FROM compte_utilisateur WHERE nom_utilisateur = ?");
        $stmt->bind_param("s", $testusername);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Nom d'utilisateur déjà pris
            header("Location: index.php?error=" . urlencode("Nom d'utilisateur déjà pris."));
            exit;
        }

        // Insertion dans la base
        $stmt = $connection->prepare("INSERT INTO compte_utilisateur (nom_utilisateur, mot_de_passe) VALUES (?, ?)");
        $stmt->bind_param("ss", $testusername, $testpassword);
        $stmt->execute();



        $connection->close();

        // Redirection finale
        header("Location: index.php?error=" . urlencode("Inscription réussie !"));
        exit;

    } catch (Exception $error) {
        die("Erreur : " . $error->getMessage());
    }
}


// Traitement de la connexion
if (isset($_POST['connexion'])) {
   
    // Sécuriser les entrées utilisateur
    $nom_utilisateur = trim($_POST['username'] ?? '');
    $mot_de_passe = trim($_POST['password'] ?? '');

    // Vérifier que les champs ne sont pas vides
    if (empty($nom_utilisateur) || empty($mot_de_passe)) {
        die("Veuillez remplir tous les champs.");
    }

    // Connexion à la base de données
    $connection = new mysqli(HOSTNAME, USERNAME, PASSWORD, 'jeu_devinette');

        // Vérifier si le fichier SQL existe avant de l'exécuter
     if (!file_exists("jeu_devinette.sql")) {
              throw new Exception("Le fichier jeu_devinette.sql est introuvable.");
        }
    

    if ($connection->connect_error) {
        die("Échec de connexion à la base de données : " . $connection->connect_error);
    }

    $stmt = $connection->prepare("SELECT nom_utilisateur, mot_de_passe FROM compte_utilisateur WHERE nom_utilisateur = ?");
    $stmt->bind_param("s", $nom_utilisateur);
    $stmt->execute();
    $result = $stmt->get_result();

    if ( $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if ($mot_de_passe === $user['mot_de_passe']) {
            $_SESSION['user'] = $user['nom_utilisateur'];
            $stmt->close();
            $connection->close();
            header("Location: jeu.php");

            exit();
        } else {
            echo "Mot de passe incorrect.";
            header("Location: connexion.php?error=" . urlencode("Mot de passe incorrect."));
        }
    } else {
        echo "Nom d'utilisateur non trouvé.";
        header("Location: connexion.php?error=" . urlencode("Nom d'utilisateur non trouvé."));
    }

    $stmt->close();
    $connection->close();

}

