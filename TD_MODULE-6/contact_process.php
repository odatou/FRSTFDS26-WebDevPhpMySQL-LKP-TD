<?php
require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Initialiser tableau pour les erreurs
    $errors = [];


    // Étape 1: Récupérer et nettoyer les données
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');



    // --- VALIDATION COUCHE 1: CHAMPS OBLIGATOIRES ---
    if (empty($name)) {
        $errors[] = "Le nom est requis";
    }
    if (empty($email)) {
        $errors[] = "L'email est requis";
    }
    if (empty($message)) {
        $errors[] = "Le message ne peut pas être vide";
    }

    // --- VALIDATION COUCHE 2: FORMAT VALIDE ---
    // Valider seulement si Couche 1 est OK
    if (empty($errors)) {
        // Vérifier la longueur du nom
        if (strlen($name) < 2) {
            $errors[] = "Le nom doit contenir au moins 2 caractères";
        }
        // Vérifier le format de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'adresse email n'est pas valide (format: user@domain.com)";
        }
        // Vérifier la longueur du message
        if (strlen($message) < 10) {
            $errors[] = "Le message doit contenir au moins 10 caractères";
        }
    }


    if (empty($errors)) {
        try {
            // Requête préparée avec 3 placeholders
            $sql = "INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)";

            $stmt = $pdo->prepare($sql);

            // Exécution avec les 3 valeurs dans l'ordre
            $stmt->execute([$name, $email, $message]);
        } catch (PDOException $e) {
            // En cas d'erreur, on attrape l'exception
            die("Erreur de connexion : " . $e->getMessage());
        }
    }


?>


    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mon Blog</title>

        <!-- Bootstrap CSS depuis CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <!-- Navbar Bootstrap -->
        <nav class="navbar navbar-dark bg-dark navbar-expand-lg">
            <div class="container-fluid">
                <a href="#" class="navbar-brand">Mon Blog</a>

                <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link " href="index.php">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="contact_form.html">Contactez-nous</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="article_form.html">Nouveau article</a>
                        </li>
                    </ul>
                    <!-- RIGHT MENU -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="signup_form.html">S'inscrire</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <?php if (!empty($errors)) { ?>
            <!-- Contenu principal -->
            <div class="container mt-5">
                <h1>Mon Blog</h1>
                <p>Bienvenue sur mon blog. Découvrez mes articles.</p>

                <!-- Section: Articles -->
                <h2 class="mt-5">Echec d'envoi de message</h2>

                <div class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
                    <?php endforeach; ?>
                </div>
                <hr />
                <a href="contact_form.html" class="btn btn-primary">Retour au formulaire de contact</a>

            </div>
        <?php } else { ?>
            <!-- Contenu principal -->
            <div class="container mt-5">
                <h1>Mon Blog</h1>
                <p>Bienvenue sur mon blog. Découvrez mes articles.</p>

                <!-- Section: Articles -->
                <h2 class="mt-5">Confirmation d'envoi de message</h2>

                <div class="alert alert-success" role="alert">
                    <p>Merci, <strong><?php echo htmlspecialchars($name) ?></strong>, pour votre message. Nous vous répondrons sous peu.</p>
                </div>


            </div>
        <?php } ?>

        <script src="script.js"></script>
    </body>

    </html>
<?php } ?>