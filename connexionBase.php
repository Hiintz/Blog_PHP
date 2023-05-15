<?php
include('secure/config.php');

try {
    $dsn = "mysql:host=$SQLhost;dbname=$SQLdb;charset=utf8mb4";
    $pdo = new PDO($dsn, $SQLlogin, $SQLpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connexion réussie !";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>
