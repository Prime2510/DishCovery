<?php
session_start();
require 'db.php';

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

$recipeID = intval($_GET['id']);

// When form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $selected = isset($_POST['access']) ? $_POST['access'] : [];

    // Fetch all users
    $users = $mysqli->query("SELECT UserID FROM User");

    while ($u = $users->fetch_assoc()) {
        $uid = $u['UserID'];

        if (in_array($uid, $selected)) {
            // Grant access
            $stmt = $mysqli->prepare("
                INSERT INTO Access (RecipeID, UserID, Status)
                VALUES (?, ?, 1)
                ON DUPLICATE KEY UPDATE Status = 1
            ");
            $stmt->bind_param("ii", $recipeID, $uid);
            $stmt->execute();
        } else {
            // Revoke access
            $stmt = $mysqli->prepare("
                UPDATE Access SET Status = 0
                WHERE RecipeID = ? AND UserID = ?
            ");
            $stmt->bind_param("ii", $recipeID, $uid);
            $stmt->execute();
        }
    }

    header("Location: manage-page.php?id=$recipeID&saved=1");
    exit;
}

// For checkboxes - retrieve all users with their current access status
$sql = "
SELECT u.UserID, u.ScreenName, u.Email, u.AvatarURL, COALESCE(a.Status, 0) AS HasAccess
FROM User u
LEFT JOIN Access a
  ON u.UserID = a.UserID AND a.RecipeID = ?
ORDER BY u.ScreenName
";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $recipeID);
$stmt->execute();
$users = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Recipe Access</title>
  <link rel="stylesheet" href="css/style.css">
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
     
      <div>
        <button type="button" class="primary-btn" style="margin: 18px 0 10px 0;" onclick="location.href='recipe-page.php'">
           Back to Recipes
        </button>
      </div>
      
      <section class="form-section">
        <?php
        $recipeTitleStmt = $mysqli->prepare("SELECT Title FROM Recipe WHERE RecipeID = ?");
        $recipeTitleStmt->bind_param("i", $recipeID);
        $recipeTitleStmt->execute();
        $recipeInfo = $recipeTitleStmt->get_result()->fetch_assoc();
        ?>
        <h2>Manage Access: <span class="recipe-title"><?= htmlspecialchars($recipeInfo['Title']) ?></span></h2>

        <h3>Share with Users</h3>

        <form action="manage-page.php?id=<?= $recipeID ?>" method="post">
        <div class="user-list">
        <?php
        // Get recipe title
        $recipeStmt = $mysqli->prepare("SELECT Title FROM Recipe WHERE RecipeID = ?");
        $recipeStmt->bind_param("i", $recipeID);
        $recipeStmt->execute();
        $recipeTitle = $recipeStmt->get_result()->fetch_assoc()['Title'];
        
        while ($userRow = $users->fetch_assoc()) {
            $avatarSrc = !empty($userRow['AvatarURL']) ? htmlspecialchars($userRow['AvatarURL']) : 'avatars/default.png';
            $checked = ($userRow['HasAccess'] == 1) ? 'checked' : '';
        ?>
          <label class="user-item">
            <img src="<?= $avatarSrc ?>" alt="User Avatar" class="avatar">
            <div class="user-info">
            <span class="email"><?= htmlspecialchars($userRow['Email']) ?></span>
            <span class="username"><?= htmlspecialchars($userRow['ScreenName']) ?></span>
            </div>
            <input type="checkbox" name="access[]" value="<?= $userRow['UserID'] ?>" <?= $checked ?>>
          </label>
        <?php } ?>
        </div>

        <div class="form-actions">
          <button type="submit" class="primary-btn">Save Changes</button>
        </div>
        </form>
      </section>
    </main>

    
    <footer id="footer-auth">
      <p class="footer-text">CS 215: Assignment 3</p>
    </footer>
  </div>
</body>
</html>
