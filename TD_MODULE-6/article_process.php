<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Initialiser tableau pour les erreurs
    $errors = [];


    // Étape 1: Récupérer et nettoyer les données
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $auteur = trim($_POST['auteur'] ?? '');
    $categorie = trim($_POST['categorie'] ?? '');
    $date = trim($_POST['date'] ?? '');



    // --- VALIDATION COUCHE 1: CHAMPS OBLIGATOIRES ---
    if (empty($titre)) {
        $errors[] = "Le titre est obligatoire";
    }
    if (empty($contenu)) {
        $errors[] = "Le contenu est obligatoire";
    }
    if (empty($auteur)) {
        $errors[] = "L'auteur est obligatoire";
    }
    if (empty($categorie)) {
        $errors[] = "La catégorie est obligatoire";
    }
    if (empty($date)) {
        $errors[] = "La date est obligatoire";
    }

    // --- VALIDATION COUCHE 2: FORMAT VALIDE ---
    // Valider seulement si Couche 1 est OK
    if (empty($errors)) {
        // Vérifier longueur du titre (5-100 caractères)
        if (!empty($titre) && (strlen($titre) < 5 || strlen($titre) > 100)) {
            $errors[] = "Le titre doit avoir entre 5 et 100 caractères";
        }

        // Vérifier longueur du contenu (20-1000 caractères)
        if (!empty($contenu) && (strlen($contenu) < 20 || strlen($contenu) > 1000)) {
            $errors[] = "Le contenu doit avoir entre 20 et 1000 caractères";
        }

        // Vérifier longueur auteur (3-50 caractères)
        if (!empty($auteur) && (strlen($auteur) < 3 || strlen($auteur) > 50)) {
            $errors[] = "L'auteur doit avoir entre 3 et 50 caractères";
        }

        // Vérifier date valide et pas dans le futur
        if (!empty($date)) {
            $dateObj = DateTime::createFromFormat('Y-m-d', $date);
            if (!$dateObj) {
                $errors[] = "La date n'est pas valide";
            } elseif ($dateObj > new DateTime()) {
                $errors[] = "La date ne peut pas être dans le futur";
            }
        }

        // Vérifier catégorie valide 
        $categories_valides = ['Tutoriels', 'Ressources', 'Actualités'];
        if (!empty($categorie) && !in_array($categorie, $categories_valides)) {
            $errors[] = "La catégorie sélectionnée n'est pas valide";
        }

        
    }


    // Déterminer si le formulaire est valide 
    $article_valide = empty($errors);
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
                            <a class="nav-link " href="contact_form.html">Contactez-nous</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="article_form.html">Nouveau article</a>
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
                <h1>Ajouter un article</h1>
                <p class="text-muted">Remplissez le formulaire ci-dessous pour créer un nouvel article sur le blog.</p>

                <!-- Section: Articles -->
                <h2 class="mt-5">Echec d'enregistrement</h2>

                <div class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
                    <?php endforeach; ?>
                </div>
                <hr />
                <button type='button' class="btn btn-primary"  onclick="history.back()">Retour au formulaire </button>

            </div>
        <?php } else { ?>
            <!-- Contenu principal -->
            <div class="container mt-5">
                <h1>Mon Blog</h1>
                <p>Bienvenue sur mon blog. Découvrez mes articles.</p>

                <!-- Section: Articles -->
                <h2 class="mt-5">Article sauvegardé</h2>

                <div class="alert alert-success" role="alert">
                    <p>Votre article "<strong><?php echo htmlspecialchars($titre)?></strong>" a été correctement sauvegardé.</p>
                </div>


            </div>
        <?php } ?>

        <script src="script.js"></script>
    </body>

    </html>
<?php } ?>