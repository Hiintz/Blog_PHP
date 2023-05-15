<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="img/icone.ico" type="image/x-icon">
    <title>Suppression - Blog du voyages dans le temps</title>
</head>

<body>
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
            session_start();
            if (isset($_SESSION['login'])) {
                echo '<a href="administration.php"><span class="material-symbols-outlined">settings_suggest</span><br>Administration</a>';
                echo '<a href="deconnexion.php"><span class="material-symbols-outlined">logout</span><br>Se déconnecter</a>';
            } else {
                // sinon on affiche un lien de connexion
                echo '<a href="connexion.php"><span class="material-symbols-outlined">login</span><br>Se connecter</a>';
            }
            include('connexionBase.php');
            ?>
        </div>
    </header>
    <main>
        <div class="firstTitle">
            <h2>Suppression Article</h2>
        </div>
        <div id="delPost">
            <?php
            session_start();
            include 'connexionBase.php';
            try {
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    // récupération des données de l'article
                    $requete = "SELECT Title, Contents FROM post WHERE Id = ?";
                    $prepare = $pdo->prepare($requete);
                    $prepare->execute([$id]);
                    $reponse = $prepare->fetch(PDO::FETCH_ASSOC);
                    // affichage des données de l'article dans un formulaire
                    echo '<form action="suppression.php" method="post" id="formDel">';
                    echo '<p>Vous êtes sur le point de supprimer l\'article suivant :</p>';
                    echo '<div>';
                    echo '<label for="title">Titre :</label><br>';
                    echo '<input type="text" name="title" id="title" value="' . $reponse['Title'] . '" readonly>';
                    echo '</div>';
                    echo '<div>';
                    echo '<label for="content">Contenu :</label><br>';
                    echo '<textarea name="content" id="content" cols="30" rows="10" readonly>' . $reponse['Contents'] . '</textarea>';
                    echo '</div>';
                    echo '<div>';
                    echo '<input type="hidden" name="id" value="' . $id . '">';
                    echo '<input type="submit" value="Supprimer">';
                    echo '</div>';
                    echo '</form>';
                } elseif (isset($_POST['id'])) {
                    $id = $_POST['id'];
                    // suppression de l'article
                    $requete = "DELETE FROM post WHERE Id = ?";
                    $prepare = $pdo->prepare($requete);
                    $prepare->execute([$id]);
                    header('Location: administration.php');
                } else {
                    header('Location: administration.php');
                }
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
            $pdo = null;
            ?>

        </div>
    </main>
    <footer>
        <p>Blog créé par Hiintz - <a href="mailto:contact@mail.fr">Une idée d'article ?</a></p>
    </footer>
</body>

</html>