<?php
include('connexionBase.php');
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'addPost':
            // récupération des données du formulaire
            $title = $_POST['title'];
            $content = $_POST['content'];
            $author = $_POST['author'];
            $category = $_POST['category'];
            $date = date('Y-m-d H:i:s');
            // insertion des données dans la base de données
            $requete = "INSERT INTO post (Title, Contents, CreationTimestamp, Author_Id, Category_Id)
                        VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($requete);
            $stmt->execute([$title, $content, $date, $author, $category]);

            // on récupère l'id du post qui vient d'être inséré
            $requete = "SELECT Id FROM post WHERE Title=? AND Contents=? AND CreationTimestamp=? AND Author_Id=? AND Category_Id=?";
            $stmt = $pdo->prepare($requete);
            $stmt->execute([$title, $content, $date, $author, $category]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $id = $data['Id'];

            //récupération de l'image
            if(isset($_FILES)) {
                $dossier = 'upload/';
                $fichier = basename($_FILES['image']['name']);
                $taille_maxi = 100000000000;
                $taille = filesize($_FILES['image']['tmp_name']);
                $extensions = array('.png', '.gif', '.jpg', '.jpeg');
                $extension = strrchr($_FILES['image']['name'], '.');
                //Début des vérifications de sécurité...
                if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
                {
                    $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg, txt ou doc...';
                }
                if($taille>$taille_maxi)
                {
                    $erreur = 'Le fichier est trop gros...';
                }
                if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
                {
                    //On formate le nom du fichier ici...
                    $fichier = strtr($fichier,
                        'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                        'abcdefghijklmnopqrstuvwxyz');
                    $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
                    if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $fichier))
                    {
                        echo 'Upload effectué avec succès !';
                    }
                    else //Sinon (la fonction renvoie FALSE).
                    {
                        echo 'Echec de l\'upload !';
                    }
                    // on insère les données dans la base de données
                    $requete = "INSERT INTO files (path, filename, postId)
                        VALUES (?, ?, ?)";
                    $stmt = $pdo->prepare($requete);
                    $stmt->execute([$dossier, $fichier, $id]);                    
                }
                else
                {
                    echo $erreur;
                }
            }


            if ($stmt->rowCount() > 0) {
                header('Location: administration.php?success=1');
            } else {
                header('Location: administration.php?error=1');
            }
            break;
        case 'addAuthor':
            // récupération des données du formulaire
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            // insertion des données dans la base de données
            $requete = "INSERT INTO author (FirstName, LastName)
                        VALUES (?, ?)";
            $stmt = $pdo->prepare($requete);
            $stmt->execute([$firstName, $lastName]);
            if ($stmt->rowCount() > 0) {
                header('Location: administration.php?success=2');
            } else {
                header('Location: administration.php?error=2');
            }
            break;
        case 'addCategory':
            // récupération des données du formulaire
            $name = $_POST['name'];
            // insertion des données dans la base de données
            $requete = "INSERT INTO category (Name)
                        VALUES (?)";
            $stmt = $pdo->prepare($requete);
            $stmt->execute([$name]);
            if ($stmt->rowCount() > 0) {
                header('Location: administration.php?success=3');
            } else {
                header('Location: administration.php?error=3');
            }
            break;
        default;
    }
} else {
    header('Location: administration.php');
}