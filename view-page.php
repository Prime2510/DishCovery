<?php
session_start();
require 'db.php';

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

$recipeID = intval($_GET['id']);
$user = $_SESSION['UserID'];

// Add note
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = trim($_POST['note']);

    if ($note !== "") {
        $stmt = $mysqli->prepare(
            "INSERT INTO Note (RecipeID, UserID, NoteText, Timestamp)
             VALUES (?, ?, ?, NOW())"
        );
        $stmt->bind_param("iis", $recipeID, $user, $note);
        $stmt->execute();
    }

    header("Location: view-page.php?id=$recipeID");
    exit;
}

// Fetch recipe data
$stmt = $mysqli->prepare("SELECT * FROM Recipe WHERE RecipeID = ?");
$stmt->bind_param("i", $recipeID);
$stmt->execute();
$recipe = $stmt->get_result()->fetch_assoc();

// Fetch notes
$stmt2 = $mysqli->prepare("
SELECT n.NoteText, n.Timestamp, u.ScreenName, u.AvatarURL
FROM Note n
JOIN User u ON n.UserID = u.UserID
WHERE n.RecipeID = ?
ORDER BY n.Timestamp ASC
");
$stmt2->bind_param("i", $recipeID);
$stmt2->execute();
$notes = $stmt2->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Recipe</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="js/eventhandler.js"></script>
</head>
<body>
  <div id="container">
    <header id="header-auth">
      <div>
        <h1>
          <span class="dish">Dish</span><span class="covery">covery</span>
        </h1>
        <form>
          <button type="button" class="logout-btn" onclick="location.href='login.php'">Logout</button>
        </form>
      </div>
    </header>
    <main id="main-center">
      <!-- Back to Recipes button in the white area -->
      <div>
        <button type="button" class="primary-btn" onclick="location.href='recipe-page.php'">
           Back to Recipes
        </button>
      </div>
      
      <section class="recipe-details">
        <h2 class="recipe-title"><?= htmlspecialchars($recipe['Title']) ?></h2>
        <p class="meta">Created: <?= date('Y-m-d h:i A', strtotime($recipe['DateCreated'])) ?></p>
        <?php if ($notes->num_rows > 0) { 
            $lastNoteTime = $mysqli->query("SELECT MAX(Timestamp) as LastTime FROM Note WHERE RecipeID = $recipeID")->fetch_assoc();
        ?>
        <p class="meta">Last Edited: <?= date('Y-m-d h:i A', strtotime($lastNoteTime['LastTime'])) ?></p>
        <?php } ?>
      </section>

      
      <section class="notes-section">
        <h3>Cooking Notes</h3>
        <?php
        if ($notes->num_rows > 0) {
            while ($note = $notes->fetch_assoc()) {
                $avatarSrc = !empty($note['AvatarURL']) ? htmlspecialchars($note['AvatarURL']) : 'avatars/default.png';
        ?>
        <div class="note">
          <img src="<?= $avatarSrc ?>" alt="User Avatar" class="avatar">
          <div class="note-content">
            <p class="note-text"><?= htmlspecialchars($note['NoteText']) ?></p>
            <p class="note-meta">Added: <?= date('Y-m-d h:i A', strtotime($note['Timestamp'])) ?> by <?= htmlspecialchars($note['ScreenName']) ?></p>
          </div>
        </div>
        <?php 
            }
        } else {
            echo '<p>No notes yet. Be the first to add one!</p>';
        }
        ?>
      </section>

      <!-- Add New Note -->
      <section class="add-note">
        <h3>Add a New Note</h3>
        <form id="note-form" action="view-page.php?id=<?= $recipeID ?>" method="post">
          <textarea id="newNote" name="note" rows="4" placeholder="Write your note here..." required></textarea>
          <div id="error-text-note" class="error-text hidden">
            Note should not be empty and less than 1300 characters.
          </div>
          <div id="charCount" class="char-counter">0/1300 characters</div>
          <div class="form-actions">
            <button type="submit" class="primary-btn">Add Note</button>
          </div>
        </form>
      </section>
    </main>
    <!-- Footer (same as login/signup) -->
    <footer id="footer-auth">
      <p class="footer-text">CS 215: Assignment 3</p>
    </footer>
  </div>
  <script src="js/eventregisternote.js"></script>
</body>
</html>
