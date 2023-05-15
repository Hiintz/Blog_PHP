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
    <title>Administration - Blog du voyages dans le temps</title>
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
                header('Location: connexion.php');
                // sinon on affiche un lien de connexion
                echo '<a href="connexion.php"><span class="material-symbols-outlined">login</span><br>Se connecter</a>';
            }
            include('connexionBase.php');
            ?>
        </div>
    </header>
    <main>
        <div>
            <h2 class="firstTitle">Administration</h2>
            <?php
            if (isset($_GET['error'])) {
                switch ($_GET['error']) {
                    case 1:
                        echo '<p class="error">Erreur lors de l\'ajout de l\'article</p>';
                        break;
                    case 2:
                        echo '<p class="error">Erreur lors de l\'ajout de l\'auteur</p>';
                        break;
                    case 3:
                        echo '<p class="error">Erreur lors de l\'ajout de la catégorie</p>';
                        break;
                    default:
                        echo '<p class="error">Erreur lors de l\'action</p>';
                        break;
                }
            } elseif (isset($_GET['success'])) {
                switch ($_GET['success']) {
                    case 1:
                        echo '<p class="success">Article ajouté</p>';
                        break;
                    case 2:
                        echo '<p class="success">Auteur ajouté</p>';
                        break;
                    case 3:
                        echo '<p class="success">Catégorie ajoutée</p>';
                        break;
                    default:
                        echo '<p class="success">Action effectuée</p>';
                        break;
                }
            }
            ?>
        </div>
        <div id="addList">
            <div id="addPost">
                <form action="addMulti.php" method="post" id="formAddPost" enctype="multipart/form-data">
                    <h2>Ajouter un article</h2>
                    <div>
                        <label for="title">Titre :</label>
                        <input type="text" name="title" id="title" required>
                    </div>
                    <div>
                        <label for="content">Contenu :</label><br>
                        <textarea name="content" id="content" cols="30" rows="10" required></textarea>
                    </div>
                    <div>
                        <label for="author">Auteur :</label>
                        <?php
                        // récupération des auteurs
                        $requete = "SELECT * FROM author";
                        $execute = $pdo->query($requete);
                        // affichage des auteurs dans une liste déroulante
                        echo '<select name="author" id="author" required>';
                        echo '<option value="" selected disabled hidden>Choisissez un auteur</option>';
                        while ($data = $execute->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . $data['Id'] . '">' . $data['FirstName'] . " " . $data['LastName'] . '</option>';
                        }
                        echo '</select>';
                        ?>
                    </div>
                    <div>
                        <label for="category">Catégorie :</label>
                        <?php
                        // récupération des catégories
                        $requete = "SELECT * FROM category";
                        $execute = $pdo->query($requete);
                        // affichage des catégories dans une liste déroulante
                        echo '<select name="category" id="category" required>';
                        echo '<option value="" selected disabled hidden>Choisissez une catégorie</option>';
                        while ($data = $execute->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . $data['Id'] . '">' . $data['Name'] . '</option>';
                        }
                        echo '</select>';
                        ?>
                    </div>
                    <div>
                        <label for="image">Image (optionnel) :</label>
                        <input type="file" name="image" id="image">
                    </div>
                    <div>
                        <input type="hidden" name="action" value="addPost">
                        <input type="submit" value="Ajouter un article">
                    </div>
                </form>
            </div>
            <div id="addAuthor">
                <form action="addMulti.php" method="post" id="formAddAut">
                    <h2>Ajouter un auteur</h2>
                    <div>
                        <label for="firstName">Prénom :</label>
                        <input type="text" name="firstName" id="firstName" required>
                    </div>
                    <div>
                        <label for="lastName">Nom :</label>
                        <input type="text" name="lastName" id="lastName" required>
                    </div>
                    <div>
                        <input type="hidden" name="action" value="addAuthor">
                        <input type="submit" value="Ajouter un auteur">
                    </div>
                </form>
            </div>
            <div id="addCategory">
                <form action="addMulti.php" method="post" id="formAddCat">
                    <h2>Ajouter une catégorie</h2>
                    <div>
                        <label for="name">Catégorie :</label>
                        <input type="text" name="name" id="name" required>
                    </div>
                    <div>
                        <input type="hidden" name="action" value="addCategory">
                        <input type="submit" value="Ajouter une catégorie">
                    </div>
                </form>
            </div>
        </div>
        <hr>
        <div>
            <h2>Liste des articles</h2>
        </div>
        <div id="listPost">
            <?php
            // récupération des articles
            $requete = "SELECT p.*, a.FirstName, a.LastName, c.Name
            FROM post p 
            LEFT JOIN author a ON a.Id=p.Author_Id 
            LEFT JOIN category c ON c.Id=p.Category_Id
            ORDER BY CreationTimestamp DESC";
            $execute = $pdo->query($requete);
            // affichage des articles sous forme de tableau
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Titre</th>';
            echo '<th>Contenu</th>';
            echo '<th>Auteur</th>';
            echo '<th>Catégorie</th>';
            echo '<th>Date / Heure</th>';
            echo '<th>Actions</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            while ($data = $execute->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . $data['Title'] . '</td>';
                $content = substr($data['Contents'], 0, 30) . '...'; // on ne garde que les 30 premiers caractères de $data['Content'
                echo '<td title="' . $data['Contents'] . '">' . $content . '</td>';
                echo '<td>' . $data['FirstName'] . " " . $data['LastName'] . '</td>';
                echo '<td>' . $data['Name'] . '</td>';
                // affichage de la date et heure sous le format dd/mm/yyyy hh:mm
                $date = date('d/m/y', strtotime($data['CreationTimestamp']));
                $heure = date('H:i', strtotime($data['CreationTimestamp']));
                echo '<td>Le ' . $date . ' à ' . $heure . '</td>';
                echo '<td>
                <a href="modification.php?id=' . $data['Id'] . '"><span class="material-symbols-outlined">edit</span></a>
                <a href="suppression.php?id=' . $data['Id'] . '"><span class="material-symbols-outlined">delete</span></a>
                </td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';



            ?>

    </main>
    <footer>
        <p>Blog créé par Hiintz - <a href="mailto:contact@mail.fr">Une idée d'article ?</a></p>
    </footer>
</body>

</html>