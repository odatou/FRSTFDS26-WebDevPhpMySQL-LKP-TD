<?php
$host = 'localhost';
$dbname = 'blog_db';
$user = 'root';
$pass = ''; // Vide sous XAMPP, 'root' sous MAMP
// DSN: Data Source Name
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {
    // Création de l'instance PDO
    $pdo = new PDO($dsn, $user, $pass);
    // Configuration des erreurs pour lever des Exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "Connexion réussie !";
} catch (PDOException $e) {
    // En cas d'erreur, on attrape l'exception
    die("Erreur de connexion : " . $e->getMessage());
}
