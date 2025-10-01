<?php
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
      <h2 class="red">Créer un compte d'utilisateur</h2>
      <div class="form">
        <fieldset>
          <legend>Formulaire d'inscription</legend>
          <form method="post" action="db_management.php">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username"><br>

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password"><br>
            <label for="confirm">Confirmer mot de passe</label>
            <input type="password" id="confirm" name="confirm"><br>
            <button type="submit" name="inscription">S'INSCRIRE</button>
            <button><a href="connexion.php">SE CONNECTER </a></button>
          </form>
        </fieldset>

         <?php if (!empty($error)) : ?>
        <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
      </div>
     
     
    </div>

    <footer>
      <p>©2025 Tous droits réservés.</p>
    </footer>
  </div>

  <script>
    document.getElementById('username').addEventListener('blur', function() {
      const username = this.value;
      if (username.length === 0) return;

      const xhr = new XMLHttpRequest();
      xhr.open("GET", "db_management.php?username=" + encodeURIComponent(username), true);

      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          const reponse = xhr.responseText.trim();
          const messageZone = document.getElementById("resultat-verif");

          if (reponse === "existe") {
            messageZone.innerText = " Ce nom d'utilisateur est déjà pris.";
            messageZone.style.color = "red";
          } else if (reponse === "disponible") {
            messageZone.innerText = " Nom d'utilisateur disponible.";
            messageZone.style.color = "green";
          } else {
            messageZone.innerText = " Erreur de vérification.";
            messageZone.style.color = "orange";
          }
        }
      };

      xhr.send();
    });
  </script>
  <div id="resultat-verif" style="margin-top: 10px;"></div>


</body>

</html>