<?php
session_start();
require 'db.php';

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);

    if ($title === "") {
        header("Location: create-page.php?error=1");
        exit;
    }

    $user = $_SESSION['UserID'];

    // Insert recipe
    $stmt = $mysqli->prepare(
        "INSERT INTO Recipe (Title, DateCreated, CreatedBy)
         VALUES (?, NOW(), ?)"
    );
    $stmt->bind_param("si", $title, $user);
    $stmt->execute();

    $recipeID = $mysqli->insert_id;

    // Give owner access
    $stmt2 = $mysqli->prepare(
        "INSERT INTO Access (RecipeID, UserID, Status)
         VALUES (?, ?, 1)"
    );
    $stmt2->bind_param("ii", $recipeID, $user);
    $stmt2->execute();

    header("Location: recipe-page.php");
    exit;
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create New Recipe</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="js/eventhandler.js"></script>
</head>
<body>
  <div id="container">
    
    <header id="header-auth">
    <div>
        <h1><span class="dish">Dish</span><span class="covery">covery</span></h1>
        <form>
            <button type="button" class="logout-btn" onclick="location.href='login.php'">Logout</button>
        </form>
    </div>
    </header>
    <main id="main-center">
      <div>
        <button type="button" class="primary-btn" onclick="location.href='recipe-page.php'">
         Back to Recipes
        </button>
      </div>
      
      <form id="recipe-form" class="auth-form" action="create-page.php" method="post">
        <h2 class="login-head">Create New Recipe</h2>
        <div class="form-input-grid">
          <label for="recipeName">Recipe Name:</label>
          <input type="text" id="recipeName" name="title" placeholder="Enter recipe name" required>
          &nbsp;
          <div id="error-text-recipeName" class="error-text hidden">
            Recipe name should not be empty and less than 256 characters.
          </div>
        </div>
        <div class="align-right">
          <input type="submit" value="Create Recipe">
        </div>
        <div class="form-actions">
          <button type="button" class="primary-btn" onclick="location.href='recipe-page.php'">Cancel</button>
        </div>
      </form>
    </main>
    <!-- Footer (same as login/signup) -->
    <footer id="footer-auth">
      <p class="footer-text">CS 215: Assignment 3</p>
    </footer>
  </div>
  <script src="js/eventregisterrecipe.js"></script>
</body>
</html>