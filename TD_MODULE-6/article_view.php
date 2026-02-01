<?php
require_once("config.php");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $article_id = trim($_GET['article']);

    try {
        // 1. Préparer la requête SQL
        $sql = "
        SELECT 
        a.id, 
          a.title as titre, 
          a.content as contenu, 
          au.name as auteur, 
          a.created_at as date, 
          c.name as categorie 

      FROM articles a
      inner join authors au on au.id = a.author_id
      inner join categories c on c.id = a.category_id
      where a.id = ? ";


        $stmt = $pdo->prepare($sql);
        // 2. Exécuter la requête
        $stmt->execute([$article_id]);

        // 3. Récupérer les résultats (fetchAll pour tout récupérer)
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        
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
                        <a class="nav-link active" aria-current="page" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="contact_form.html">Contactez-nous</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="article_form.html">Nouveau article</a>
                    </li>
                </ul>
                <!-- RIGHT MENU -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="signup_form.html">S'inscrire</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container mt-5">
        <h1>Mon Blog</h1>
        <p class="text-muted">Remplissez le formulaire ci-dessous pour créer un nouvel article sur le blog.</p>

        <div class="card shadow-sm">

            <!-- Titre de l'article -->
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><?php echo htmlspecialchars($article['titre']); ?></h3>
            </div>

            <!-- Corps de l'article -->
            <div class="card-body">

                <!-- Catégorie -->
                <span class="badge bg-secondary mb-3"><?php echo htmlspecialchars($article['categorie']); ?></span>


                <!-- Contenu -->
                <p class="card-text mt-3">
                    <?php echo nl2br(htmlspecialchars($article['contenu'])); ?>
                </p>

            </div>

            <!-- Auteur -->
            <div class="card-footer text-muted text-end">
                Rédigé par <strong><?php echo htmlspecialchars($article['auteur']); ?></strong>

            </div>

        </div>






    </div>

    <script src="script.js"></script>
</body>

</html>