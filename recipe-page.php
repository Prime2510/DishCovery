<?php
session_start();
require 'db.php';

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['UserID'];

$sql = "
SELECT r.RecipeID, r.Title, r.DateCreated,
       MAX(n.Timestamp) AS LastNote,
       COUNT(n.NoteID) AS NoteCount
FROM Recipe r
JOIN Access a ON r.RecipeID = a.RecipeID
LEFT JOIN Note n ON r.RecipeID = n.RecipeID
WHERE a.UserID = ? AND a.Status = 1
GROUP BY r.RecipeID
ORDER BY r.DateCreated DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user);
$stmt->execute();
$recipes = $stmt->get_result();

// now loop through $recipes in your HTML
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Page</title>
    <link rel="stylesheet" href="css/style.css">   
</head>
<body>
    <div id="recipe-container">
    <header id="header-auth">
        <div>
            <h1><span class="dish">Dish</span><span class="covery">covery</span></h1>
            <form>
                <button type="button" class="logout-btn" onclick="location.href='login.php'">Logout</button>
            </form>
        </div>
    </header>
    
    <section class="recipes-section1">
      <h2>My Recipes</h2>
      <?php
      // Separate owned vs shared
      $myRecipes = [];
      $sharedRecipes = [];
      
      $recipes->data_seek(0); // Reset pointer
      while ($recipe = $recipes->fetch_assoc()) {
          // Check if user created this recipe
          $checkOwner = $mysqli->prepare("SELECT CreatedBy FROM Recipe WHERE RecipeID = ?");
          $checkOwner->bind_param("i", $recipe['RecipeID']);
          $checkOwner->execute();
          $ownerResult = $checkOwner->get_result()->fetch_assoc();
          
          if ($ownerResult['CreatedBy'] == $user) {
              $myRecipes[] = $recipe;
          } else {
              $sharedRecipes[] = $recipe;
          }
      }
      
      // Display owned recipes
      foreach ($myRecipes as $recipe) {
          $lastNote = $recipe['LastNote'] ? date('m/d/Y', strtotime($recipe['LastNote'])) : 'No notes yet';
          $dateCreated = date('m/d/Y', strtotime($recipe['DateCreated']));
      ?>
      <div class="card">
        <h3 class="recipe-title"><?= htmlspecialchars($recipe['Title']) ?></h3>
        <p class="recipe-meta">Created by: <?= htmlspecialchars($_SESSION['ScreenName']) ?></p>
        <p class="recipe-meta">Created on: <?= $dateCreated ?></p>
        <p class="recipe-meta">Last edited: <?= $lastNote ?></p>
        <p class="recipe-meta">Notes: <?= $recipe['NoteCount'] ?></p>
        <div class="card-actions">
          <button onclick="location.href='view-page.php?id=<?= $recipe['RecipeID'] ?>'">View Recipe</button>
          <button onclick="location.href='manage-page.php?id=<?= $recipe['RecipeID'] ?>'">Manage Access</button>
        </div>
      </div>
      <?php } ?>

      <div class="card create-card">
        <button class="create-btn" onclick="location.href='create-page.php'">+</button>
        <p>Create New Recipe</p>
      </div>

      
    </section>

    <!-- Shared -->
    <section class="recipes-section2">
      <h2>Shared</h2>
      <?php
      // Display shared recipes
      foreach ($sharedRecipes as $recipe) {
          $lastNote = $recipe['LastNote'] ? date('m/d/Y', strtotime($recipe['LastNote'])) : 'No notes yet';
          $dateCreated = date('m/d/Y', strtotime($recipe['DateCreated']));
          
          // Get creator name
          $getCreator = $mysqli->prepare("SELECT u.ScreenName FROM User u JOIN Recipe r ON u.UserID = r.CreatedBy WHERE r.RecipeID = ?");
          $getCreator->bind_param("i", $recipe['RecipeID']);
          $getCreator->execute();
          $creator = $getCreator->get_result()->fetch_assoc();
      ?>
      <div class="card">
        <h3 class="recipe-title"><?= htmlspecialchars($recipe['Title']) ?></h3>
        <p class="recipe-meta">Shared by: <?= htmlspecialchars($creator['ScreenName']) ?></p>
        <p class="recipe-meta">Created on: <?= $dateCreated ?></p>
        <p class="recipe-meta">Last edited: <?= $lastNote ?></p>
        <p class="recipe-meta">Notes: <?= $recipe['NoteCount'] ?></p>
        <div class="card-actions">
          <button onclick="location.href='view-page.php?id=<?= $recipe['RecipeID'] ?>'">View Recipe</button>
        </div>
      </div>
      <?php } ?>
    </section>
    

    <!-- Logout -->
    
            <footer id="footer-auth">
            
            <p class="footer-text">CS 215: Assignment 3</p>
            
        </footer>
    </div>
     
</body>
</html>