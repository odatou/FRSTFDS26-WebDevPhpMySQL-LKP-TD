<?php
require_once('config.php');
// ÉTAPE 1: Créer un tableau d'articles

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
      ORDER BY a.created_at DESC;
";
  $stmt = $pdo->prepare($sql);
  // 2. Exécuter la requête
  $stmt->execute();
  // 3. Récupérer les résultats (fetchAll pour tout récupérer)
  $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  // En cas d'erreur, on attrape l'exception
  die("Erreur de connexion : " . $e->getMessage());
}

// Construire la liste des auteurs uniques
$authors = [];

foreach ($articles as $article) {
  if (!in_array($article['auteur'], $authors, true)) {
    $authors[] = $article['auteur'];
  }
}

function obtenirArticlesParAuteur($articles, $auteur)
{
  return array_filter($articles, function ($article) use ($auteur) {
    return $article['auteur'] === $auteur;
  });
}



// FONCTION : Compter le nombre d'articles
function compterArticles($articles)
{
  return count($articles);
}
// FONCTION : Obtenir un article par ID
function obtenirArticleParId($articles, $id)
{
  foreach ($articles as $article) {
    if ($article["id"] === $id) {
      return $article;
    }
  }
  return null;
}
/** 
 * Filtre les articles pour retourner seulement ceux d'un auteur spécifique 
 *  
 * @param array $articles Tableau d'articles 
 * @param string $author Nom d'auteur à filtrer 
 * @return array Tableau d'articles filtrés 
 */

function filterByAuthor($articles, $author)
{
  return array_filter($articles, function ($article) use ($author) {
    return $article['auteur'] === $author;
  });
}

/** 
 * Trie les articles par date (décroissant: plus récent en premier) 
 * NOTE: usort() modifie le tableau EN PLACE 
 *  
 * @param array $articles Tableau d'articles (modifié en place) 
 * @return array Articles triés (également modifié paramètre) 
 */
function sortByDate(&$articles)
{
  usort($articles, function ($a, $b) {
    // Convertir dates en timestamps pour comparaison numérique 
    $dateA = strtotime($a['date']);
    $dateB = strtotime($b['date']);
    // Retourner: $dateB - $dateA pour DÉCROISSANT 
    // (plus récent en premier) 
    return $dateB - $dateA;
  });
  return $articles;
}


function afficherArticle($article)
{

  $titre = htmlspecialchars($article['titre']);
  $auteur = htmlspecialchars($article['auteur']);
  $contenu = htmlspecialchars($article['contenu']);
  $date = htmlspecialchars($article['date']);
  $categorie = htmlspecialchars($article['categorie']);

  return "
        <div class='col-md-4'>
            <div class='card'>
                <div class='card-body'>
                    <span class='badge bg-info'>$categorie</span>
                    <h5 class='card-title'>$titre</h5>
                    <p class='card-text'>$contenu</p>
                    <small class='text-muted'>Par $auteur - $date</small>
                    <div>
                        <a href='article_view.php?article=$article[id]'class='btn btn-primary mt-2'>Lire plus</a>
                    </div>
                </div>
            </div>
        </div>";
}

/** 
 * Extrait toutes les catégories uniques du tableau d'articles 
 *  
 * @param array $articles - Tableau contenant les articles 
 * @return array - Tableau des catégories uniques (pas de doublons) 
 */
function getCategoriesList($articles)
{

  $categories = [];

  foreach ($articles as $article) {
    // Check if category is not already in the list 
    if (!in_array($article['categorie'], $categories)) {
      $categories[] = $article['categorie'];
    }
  }

  return $categories;
}

/** 
 * Filtre les articles par catégorie 
 *  
 * @param array $articles - Tableau d'articles 
 * @param string $categorie - Catégorie à filtrer 
 * @return array - Articles filtrés 
 */
function filterByCategory($articles, $categorie)
{

  $filtered = [];
  if ($categorie === 'Toutes') {
    return $articles;
  }

  foreach ($articles as $article) {
    var_dump($article['categorie'], $categorie);
    if ($article['categorie'] == $categorie) {
      var_dump("match");
      $filtered[] = $article;
    }
  }

  return $filtered;
}

// Récupérer le filtre optionnel depuis l'URL 
$selectedAuthor = $_GET['author'] ?? null;


// Appliquer le filtre si un auteur est sélectionné
if ($selectedAuthor) {
  $filtered = filterByAuthor($articles, $selectedAuthor);
} else {
  // Sinon, utiliser tous les articles 
  $filtered = $articles;
}


// Récupérer liste des catégories 
$categories = getCategoriesList($filtered);

// Déterminer catégorie sélectionnée (optionnel: par défaut "Toutes") 

$selected_category = $_GET['category'] ?? 'Toutes';

if ($selected_category) {
  $displayed_articles = filterByCategory($filtered, $selected_category);
} else {
  // Sinon, utiliser tous les articles filtrés par auteur
  $displayed_articles = $filtered;
}



sortByDate($displayed_articles);

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
            <a class="nav-link" href="contact_form.html">Contactez-nous</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="article_form.html">Nouveau article</a>
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


  <!-- Contenu principal -->
  <div class="container mt-5">
    <h1>Mon Blog</h1>
    <p>Bienvenue sur mon blog. Découvrez mes articles.</p>

    <!-- Section: Articles -->
    <h2 class="mt-5">Mes Articles</h2>

    <!-- BOUTONS DE FILTRE -->
    <div class="mb-4">
      <h5>Filtrer :</h5>
      <a href="?" class="btn btn-outline-secondary">Tous les articles</a>
      <?php foreach ($authors as $author): ?>
        <?php $isActive = ($selectedAuthor === $author) ? ' active' : ''; ?>
        <a
          href="?author=<?= urlencode($author) ?>"
          class="btn btn-outline-primary<?= $isActive ?>">
          <?= htmlspecialchars($author) ?>
        </a>
      <?php endforeach; ?>
    </div>


    <!-- Boutons de catégories -->
    <div class="category-buttons">
      <a class="btn btn-outline-primary"
        href="?category=Toutes">
        Toutes les catégories
      </a>
      <?php foreach ($categories as $cat): ?>
        <?php $isActiveCategory = ($selected_category === $cat) ? ' active' : ''; ?>
        <?php $cat_safe = htmlspecialchars($cat); ?>
        <a class="btn btn-outline-secondary <?= $isActiveCategory ?>"
          href="?category=<?= urlencode($cat_safe) ?>">
          <?= $cat_safe ?>
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Grille d'articles -->
    <div class="row g-3 mt-3" id="conteneurArticles">




      <?php

      echo "<p class='text-muted'>" . count($displayed_articles) . " article(s) trouvé(s)</p>";
      // Afficher les articles 
      if (empty($displayed_articles)) {
        echo "<div class='alert alert-info'>Aucun article trouvé pour cet auteur.</div>";
      } else {
        foreach ($displayed_articles as $article) {
          echo afficherArticle($article);
        }
      }

      ?>

      <!-- Articles cachés (montrés au clic du bouton) -->
      

    </div>

    
      

  </div>

  <script src="script.js"></script>
</body>

</html>