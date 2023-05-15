<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="shortcut icon" href="img/icone.ico" type="image/x-icon">
    <title>Blog du voyages dans le temps</title>
</head>

<body>
    <?php include('connexionBase.php'); ?>

    <header>
        <div>
            <a href="index.php"><span class="material-symbols-outlined">home</span><br>Accueil</a>
        </div>
        <div id="titre">
            <img src="img/icone.png" alt="logo">
            <h1>Blog du voyages dans le temps</h1>
        </div>
        <div id="coDeco">
            <?php
            // si l'utilisateur est connecté, on affiche un lien de déconnexion
            session_start();
            if (isset($_SESSION['login'])) {
                echo '<a href="administration.php"><span class="material-symbols-outlined">settings_suggest</span><br>Administration</a>';
                echo '<a href="deconnexion.php"><span class="material-symbols-outlined">logout</span><br>Se déconnecter</a>';
            } else {
                // sinon on affiche un lien de connexion
                echo '<a href="connexion.php"><span class="material-symbols-outlined">login</span><br>Se connecter</a>';
            }
            ?>
        </div>
    </header>
    <main>
        <div class="articles">
            <?php
            // récupération des articles en base de données
            $requete = "SELECT p.*, a.FirstName, a.LastName, c.Name
                        FROM post p 
                        LEFT JOIN author a ON a.Id=p.Author_Id 
                        LEFT JOIN category c ON c.Id=p.Category_Id
                        ORDER BY Id DESC";
            $execute = $pdo->query($requete);

            // affichage des articles
            while ($data = $execute->fetch()) {
                echo '<div class="article"><a href="article.php?id=' . $data['Id'] . '">';
                echo '<h2>' . $data['Title'] . '</h2>';
                // affichage de la date et heure sous le format dd/mm/yyyy hh:mm
                $date = date('d/m/y', strtotime($data['CreationTimestamp']));
                $heure = date('H:i', strtotime($data['CreationTimestamp']));
                echo '<p>Créé le <u>' . $date . '</u> à <u>' . $heure . '</u><br>par <u>' . $data['FirstName'] . ' ' . $data['LastName'] . '</u></p>';
                echo '<p><u>Catégorie:</u> ' . $data['Name'] . '</p><br>';
                // récupération des 100 premiers caractères de la chaîne de caractères
                $contenu = substr($data['Contents'], 0, 100);
                echo '<p title="' . $data['Contents'] . '">' . $contenu . '...</p>';
                echo '</a></div>';
            }
            ?>


        </div>
    </main>
    <footer>
        <p>Blog créé par Hiintz - <a href="mailto:contact@mail.fr">Une idée d'article ?</a></p>
    </footer>
    <?php $pdo = null; ?>
</body>

</html>