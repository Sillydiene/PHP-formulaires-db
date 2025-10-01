<?php

require_once 'db_management.php';

$error = "";
if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>DeviNet - Jeu de devinettes</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .error{
      color: red;
      font-weight: bold;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1><span class="black">Devi</span><span class="green">Net</span> <a href="#">Jeu de devinettes</a></h1>
    </header>

    <div class="box">
      <h2 class="red">Connexion</h2>
      <div class="form">
        <fieldset>
          <legend>Formulaire de connexion</legend>
          <form  method="post" action="db_management.php">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required><br>

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required><br>

            <button > <a href="index.php"> S'INSCRIRE </a></button>
            <button type="submit" name="connexion" >SE CONNECTER</button>
          </form>
          <?php if (!empty($error)) : ?>
          <div class="error"><?= $error ?></div>
          <?php endif; ?>
        </fieldset>
      </div>
    </div>

    <footer>
      <p>©2025 Tous droits réservés.</p>
    </footer>
  </div>
</body>
</html>

