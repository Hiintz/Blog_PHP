<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="shortcut icon" href="img/icone.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <title>Modification - Blog du voyages dans le temps</title>
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
            <h2>Modification Article</h2>
        </div>
        <div id="modPost">
            <?php
            session_start();

            if (isset($_SESSION['login'])) {
                require_once 'connexionBase.php';

                if (isset($_GET['id'])) {
                    $id = $_GET['id'];

                    // Récupération des données de l'article
                    $requete = "SELECT Title, Contents 
                    FROM post 
                    WHERE Id = :id";
                    $stmt = $pdo->prepare($requete);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    $reponse = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Affichage des données de l'article dans un formulaire
                    echo '<form action="modification.php" method="post" id="formModif">
                    <div>
                    <label for="title">Titre :</label><br>
                    <input type="text" name="title" id="title" value="' . $reponse['Title'] . '" required>
                    </div>
                    <div>
                    <label for="content">Contenu :</label><br>
                    <textarea name="content" id="content" cols="30" rows="10" required>' . $reponse['Contents'] . '</textarea>
                    <div>
                    <input type="hidden" name="id" value="' . $id . '">
                    <input type="submit" value="Modifier">
                    </div>
                    </form>';
                } elseif (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['content'])) {
                    $id = $_POST['id'];
                    $title = $_POST['title'];
                    $content = $_POST['content'];


                    // Modification de l'article
                    $requete = "UPDATE post 
                    SET Title = :title, Contents = :content 
                    WHERE Id = :id";
                    $stmt = $pdo->prepare($requete);
                    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
                    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();

                    header('Location: administration.php');
                } else {
                    header('Location: administration.php');
                }
            } else {
                header('Location: connexion.php');
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