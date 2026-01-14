<?php
// --- Tableau d'articles (TD Module 3 + ajout catégories pour Étape 7) ---
$articles = [
    ['id'=>1,'titre'=>'Mon premier article','contenu'=>'Lorem ipsum dolor sit amet...','auteur'=>'Alice','date'=>'2025-01-10','categorie'=>'Tutoriels'],
    ['id'=>2,'titre'=>'Deuxième article','contenu'=>'Consectetur adipiscing elit...','auteur'=>'Bob','date'=>'2025-01-11','categorie'=>'Ressources'],
    ['id'=>3,'titre'=>'Troisième article','contenu'=>'Sed do eiusmod tempor incididunt...','auteur'=>'Alice','date'=>'2025-01-09','categorie'=>'Actualités'],
    ['id'=>4,'titre'=>'Quatrième article','contenu'=>'Ut labore et dolore magna aliqua...','auteur'=>'Charlie','date'=>'2025-01-12','categorie'=>'Tutoriels'],
    ['id'=>5,'titre'=>'Cinquième article','contenu'=>'Ut enim ad minim veniam...','auteur'=>'Bob','date'=>'2025-01-08','categorie'=>'Ressources'],
];

// --- Fonction displayArticle() du Coding Live ---
function displayArticle($article) {
    return "
    <div class='card mb-3'>
        <div class='card-body'>
            <h5 class='card-title'>" . htmlspecialchars($article['titre']) . "</h5>
            <p class='card-text'>" . htmlspecialchars($article['contenu']) . "</p>
            <small class='text-muted'>Par " . htmlspecialchars($article['auteur']) . " - " . $article['date'] . "</small>
            <span class='badge bg-info ms-2'>" . htmlspecialchars($article['categorie']) . "</span>
        </div>
    </div>";
}

// --- TD Module 3 ---
// Filtrer par auteur
function filterByAuthor($articles, $author) {
    return array_filter($articles, fn($article) => $article['auteur'] === $author);
}

// Trier par date décroissante
function sortArticlesByDate(&$articles) {
    usort($articles, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']); // Décroissant
    });
}

// --- Gestion filtre TD simple ---
$selectedAuthor = $_GET['author'] ?? null;

// Filtrage par auteur si présent
$filteredArticles = $selectedAuthor ? filterByAuthor($articles, $selectedAuthor) : $articles;

// Tri par date décroissante
sortArticlesByDate($filteredArticles);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog d'Articles - TD Module 3</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">

<div class="container bg-white p-4 shadow-sm rounded">
    <h1 class="mb-4">Blog d'Articles - TD Module 3</h1>

    <!-- Boutons filtrage par auteur -->
    <div class="mb-3">
        <strong>Auteurs :</strong>
        <a href="index.php" class="btn btn-secondary btn-sm me-2">Tous</a>
        <?php foreach(array_unique(array_column($articles,'auteur')) as $author): ?>
            <a href="index.php?author=<?php echo urlencode($author); ?>" class="btn btn-outline-primary btn-sm me-2 <?php echo ($selectedAuthor === $author ? 'active' : ''); ?>">
                <?php echo htmlspecialchars($author); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <hr>

    <!-- Compteur -->
    <p class="text-muted">
        <?php echo count($filteredArticles); ?> article(s)
        <?php if($selectedAuthor) echo " de l'auteur : <strong>".htmlspecialchars($selectedAuthor)."</strong>"; ?>
    </p>

    <!-- Affichage articles -->
    <?php if(empty($filteredArticles)): ?>
        <div class="alert alert-info">Aucun article trouvé.</div>
    <?php else: ?>
        <?php foreach($filteredArticles as $article): ?>
            <?php echo displayArticle($article); ?>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

</body>
</html>
