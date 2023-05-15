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
    <title>Article - Blog du voyages dans le temps</title>
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
        <?php
        if (isset($_GET['id'])) {
            if (isset($_POST['author']) && isset($_POST['comment'])) {
                $author = $_POST['author'];
                $comment = $_POST['comment'];
                $id = $_GET['id'];
                $requete = "INSERT INTO comment (NickName, Contents, CreationTimestamp, Post_Id)
                VALUES (:author, :comment, NOW(), :id)";
                $stmt = $pdo->prepare($requete);
                $stmt->execute(['author' => $author, 'comment' => $comment, 'id' => $id]);
                header('Location: article.php?id=' . $id);
            }
            // récupération de l'image de l'article si elle existe
            $requete = "SELECT path, filename FROM files WHERE postId=:id";
            $stmt = $pdo->prepare($requete);
            $stmt->execute(['id' => $_GET['id']]);
            $img = $stmt->fetch(PDO::FETCH_ASSOC);

            // récupération du post en base de données
            $id = $_GET['id'];
            $requete = "SELECT p.*, a.FirstName, a.LastName, c.Name
            FROM post p 
            LEFT JOIN author a ON a.Id=p.Author_Id 
            LEFT JOIN category c ON c.Id=p.Category_Id
            WHERE p.Id=:id";
            $stmt = $pdo->prepare($requete);
            $stmt->execute(['id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            echo '<div class="articleSolo">';
            echo '<h2>' . $data['Title'] . '</h2>';
            echo '<p><u>' . $data['CreationTimestamp'] . '</u> par <u>' . $data['FirstName'] . ' ' . $data['LastName'] . '</u></p>';
            echo '<p><u>Catégorie:</u> ' . $data['Name'] . '</p><br>';
            if ($img) {
                echo '<img src="' . $img['path'] . $img['filename'] . '" alt="image article" class="imgArticle"><br>';
            }
            echo '<p>' . $data['Contents'] . '</p>';
            echo '</div>';
            echo '<hr>';
            echo '<h4><u>Commentaires :</u></h4>';
            // Affichage du formulaire de commentaire
            echo '<form action="article.php?id=' . $id . '" method="post" id="formComment">';
            echo '<label for="author">Pseudo :</label>';
            echo '<input type="text" name="author" id="author" required>';
            echo '<label for="comment">Commentaire :</label>';
            echo '<textarea name="comment" id="comment" cols="30" rows="10" required></textarea>';
            echo '<input type="submit" value="Envoyer">';
            echo '</form>';
            // récupération des commentaires en base de données
            $requete = "SELECT *
            FROM comment
            WHERE Post_Id=:id";
            $stmt = $pdo->prepare($requete);
            $stmt->execute(['id' => $id]);
            // affichage des commentaires
            echo '<div class="comments">';
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="comment">';
                // affichage de la date et heure sous le format dd/mm/yyyy hh:mm
                $date = date('d/m/y', strtotime($data['CreationTimestamp']));
                $heure = date('H:i', strtotime($data['CreationTimestamp']));
                echo '<p><u>' . $data['NickName'] . '</u> le <u>' . $date . '</u> à <u>' . $heure . '</u></p>';
                echo '<p>' . $data['Contents'] . '</p>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            header('Location: index.php');
        }
        ?>

    </main>
    <footer>
        <p>Blog créé par Hiintz - <a href="mailto:contact@mail.fr">Une idée d'article ?</a></p>
    </footer>

    <?php $conn = null; ?>
</body>

</html>