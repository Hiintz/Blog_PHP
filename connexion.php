<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="shortcut icon" href="img/icone.ico" type="image/x-icon">
    <title>Connexion - Blog du voyages dans le temps</title>
</head>

<body>
    <?php 
    session_start();
    if(isset ($_SESSION['login'])) {
        header('Location: index.php');
    }
    include('connexionBase.php'); 
    ?>

    <header>
        <div>
            <a href="index.php"><span class="material-symbols-outlined">home</span><br>Accueil</a>
        </div>
        <div id="titre">
            <img src="img/icone.png" alt="logo">
            <h1>Blog du voyages dans le temps</h1>
        </div>
        <div>
        <a href="connexion.php"><span class="material-symbols-outlined">login</span><br>Se connecter</a>
        </div>
    </header>
    <main>
        <form action="verification.php" method="post" id="connexion">
            <h2>Connexion</h2>
            <div>
                <label for="login">Login :</label><br>
                <input type="text" name="login" id="login" required>
            </div>
            <div>
                <label for="password">Mot de passe :</label><br>
                <input type="password" name="password" id="password" required>
            </div>
            <div>
                <input type="submit" value="Se connecter">
            </div>
            <br>
        </form>
        <div class="error">
            <?php
            if (isset($_GET['error'])) {
                echo "<p>Identifiant ou mot de passe incorrect</p>";
            }
            ?>
        </div>
    </main>
    <footer>
        <p>Blog créé par Hiintz - <a href="mailto:contact@mail.fr">Une idée d'article ?</a></p>
    </footer>
    <?php $conn = null; ?>
</body>

</html>