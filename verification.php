<?php 
    include 'connexionBase.php';
    if(isset($_POST['login']) && isset($_POST['password'])) {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $requete = "SELECT count(*) 
        FROM users 
        WHERE username=:login 
        AND password=:password";
        $stmt = $pdo->prepare($requete);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        if($count == 1) {
            session_start();
            $_SESSION['login'] = $login;
            header('Location: administration.php');
            exit();
        } else {
            header('Location: connexion.php?error=1');
            exit();
        }
    } else {
        header('Location: connexion.php?error=1');
        exit();
    }
?>
