<?php
// Tableau initial d'articles (Coding Live)
$articles = [
    ['id' => 1, 'titre' => 'Mon premier article', 'contenu' => 'Lorem ipsum dolor sit amet...', 'auteur' => 'Alice', 'date' => '2025-01-10'],
    ['id' => 2, 'titre' => 'Deuxième article', 'contenu' => 'Consectetur adipiscing elit...', 'auteur' => 'Bob', 'date' => '2025-01-11'],
    ['id' => 3, 'titre' => 'Troisième article', 'contenu' => 'Sed do eiusmod tempor incididunt...', 'auteur' => 'Alice', 'date' => '2025-01-09'],
    ['id' => 4, 'titre' => 'Quatrième article', 'contenu' => 'Ut labore et dolore magna aliqua...', 'auteur' => 'Charlie', 'date' => '2025-01-12'],
    ['id' => 5, 'titre' => 'Cinquième article', 'contenu' => 'Ut enim ad minim veniam, quis...', 'auteur' => 'Bob', 'date' => '2025-01-08'],
];

// --- Fonction displayArticle (Coding Live) ---
function displayArticle($article) {
    return "
    <div class='card mb-3'>
        <div class='card-body'>
            <h5 class='card-title'>" . htmlspecialchars($article['titre']) . "</h5>
            <p class='card-text'>" . htmlspecialchars($article['contenu']) . "</p>
            <small class='text-muted'>Par " . htmlspecialchars($article['auteur']) . " - " . $article['date'] . "</small>
        </div>
    </div>";
}

// --- Étape 4: Filtrer par auteur ---
function filterByAuthor($articles, $author) {
    return array_filter($articles, function($article) use ($author) {
        return $article['auteur'] === $author;
    });
}

// --- Étape 5: Trier par date décroissante ---
function sortByDate(&$articles) {
    usort($articles, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
}

// --- Étape 6: Traitement GET pour filtrage interactif ---
$selectedAuthor = $_GET['author'] ?? null;

if ($selectedAuthor) {
    $filteredArticles = filterByAuthor($articles, $selectedAuthor);
} else {
    $filteredArticles = $articles;
}

sortByDate($filteredArticles);

// --- Liste unique des auteurs pour boutons ---
$authorsList = array_unique(array_column($articles, 'auteur'));

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">

<div class="container bg-white p-4 shadow-sm rounded">
    <h1 class="mb-4">Blog d'Articles </h1>

    
    <div class="mb-4">
        <a href="index.php" class="btn btn-secondary me-2">Tous les articles</a>
        <?php foreach ($authorsList as $author): ?>
            <a href="index.php?author=<?php echo urlencode($author); ?>" class="btn btn-outline-primary me-2 <?php echo ($selectedAuthor === $author ? 'active' : ''); ?>">
                <?php echo htmlspecialchars($author); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <hr>

    <p class="text-muted">
        <?php echo count($filteredArticles); ?> article(s) trouvé(s)
        <?php if($selectedAuthor) echo " pour l'auteur : <strong>" . htmlspecialchars($selectedAuthor) . "</strong>"; ?>
    </p>

    <?php if (empty($filteredArticles)): ?>
        <div class='alert alert-info'>Aucun article trouvé pour cet auteur.</div>
    <?php else: ?>
        <?php foreach ($filteredArticles as $article): ?>
            <?php echo displayArticle($article); ?>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

</body>
</html>
