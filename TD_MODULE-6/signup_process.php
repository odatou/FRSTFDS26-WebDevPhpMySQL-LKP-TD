<?php


require_once 'config.php'; 

$errors = [];


// 1. RÉCUPÉRATION DES DONNÉES

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';
$terms = $_POST['terms'] ?? null;


// 2. VALIDATION COUCHE 1 (OBLIGATOIRE)

if ($username === '') {
    $errors[] = "Le nom d'utilisateur est obligatoire.";
}

if ($email === '') {
    $errors[] = "L'adresse email est obligatoire.";
}

if ($password === '') {
    $errors[] = "Le mot de passe est obligatoire.";
}

if ($password_confirm === '') {
    $errors[] = "La confirmation du mot de passe est obligatoire.";
}

if ($terms === null) {
    $errors[] = "Vous devez accepter les conditions d'utilisation.";
}


// 3. VALIDATION COUCHE 2 (FORMAT / LONGUEUR)

if ($username !== '') {
    if (strlen($username) < 3 || strlen($username) > 20) {
        $errors[] = "Le nom d'utilisateur doit contenir entre 3 et 20 caractères.";
    }

    if (!preg_match('/^[a-zA-Z0-9-]+$/', $username)) {
        $errors[] = "Le nom d'utilisateur ne peut contenir que des lettres, chiffres et tirets.";
    }
}

if ($email !== '') {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Le format de l'adresse email est invalide.";
    }
}

if ($password !== '') {
    if (strlen($password) < 8) {
        $errors[] = "Le mot de passe doit contenir au minimum 8 caractères.";
    }
}

if ($password !== '' && $password_confirm !== '') {
    if ($password !== $password_confirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
}


// 4. AFFICHAGE DES ERREURS DE VALIDATION

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
    }
    echo '<a href="signup_form.html"> Retour au formulaire</a>';
    exit;
}


// 5. DOUBLONS + INSERTION

try {
    // Vérification des doublons (email) 
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $emailExists = $stmt->fetchColumn();

    if ($emailExists > 0) {
        echo '<div class="alert alert-danger">
                Cet email est déjà associé à un compte. Veuillez en utiliser un autre.
              </div>';
        echo '<a href="signup_form.html">⬅ Retour au formulaire</a>';
        exit;
    }

    //  Insertion utilisateur 
   
    $stmt = $pdo->prepare(
        "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
    );

    $stmt->execute([$username, $email, $password]);

   
    // 6. AFFICHAGE DU SUCCÈS
    
    echo '<div class="alert alert-success">Inscription réussie !</div>';
    echo '<p><strong>Nom d\'utilisateur :</strong> ' . htmlspecialchars($username) . '</p>';
    echo '<p><strong>Email :</strong> ' . htmlspecialchars($email) . '</p>';
    echo '<a href="index.php"> Accueil du blog</a> | ';
    echo '<a href="create_article.php"> Créer un article</a>';

} catch (PDOException $e) {
    
    // 7. GESTION DES ERREURS PDO
  
    error_log("Erreur inscription : " . $e->getMessage());

    echo '<div class="alert alert-danger">
            Une erreur est survenue lors de l\'inscription. Veuillez réessayer plus tard.
          </div>';
    echo '<a href="signup_form.html"> Retour au formulaire</a>';
}
