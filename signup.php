<?php
session_start();
require 'db.php'; // your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $screen = trim($_POST['screenname']);
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);

    // Validate required fields are present
    if ($screen === "" || $email === "" || $pass === "") {
        header("Location: signup.php?error=1");
        exit;
    }

    // Basic validation for illegal data
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: signup.php?error=1");
        exit;
    }

    // Determine avatar filename based on uploaded file
    $avatarPath = "../uploads/default.png"; // Default fallback
    
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            // We'll use a placeholder for userId and update after insert
            $avatarPath = "../uploads/USERID_PLACEHOLDER." . $ext;
        }
    }

    // Insert into database with avatar path template
    $stmt = $mysqli->prepare(
        "INSERT INTO User (ScreenName, Email, Password, AvatarURL)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("ssss", $screen, $email, $pass, $avatarPath);
    
    if ($stmt->execute()) {
        // Get the newly created user ID
        $userId = $mysqli->insert_id;
        $stmt->close();
        
        // Avatar upload - move to uploads folder with userId as filename
        $finalAvatarPath = "../uploads/default.png"; // Default if no upload
        
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                // Name file as userId.ext (e.g., "1.jpg", "2.png")
                $fileName = $userId . "." . $ext;
                $dest = "../uploads/" . $fileName;

                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) {
                    $finalAvatarPath = "../uploads/" . $userId . "." . $ext;
                }
            }
        }
        
        // Always update the avatar path with the actual userId in the filename
        $updateStmt = $mysqli->prepare("UPDATE User SET AvatarURL = ? WHERE UserID = ?");
        $updateStmt->bind_param("si", $finalAvatarPath, $userId);
        $updateStmt->execute();
        $updateStmt->close();
        
        // Grant the new user access to all existing recipes
        $grantAccessStmt = $mysqli->prepare(
            "INSERT INTO Access (RecipeID, UserID, Status)
             SELECT RecipeID, ?, 1 FROM Recipe"
        );
        $grantAccessStmt->bind_param("i", $userId);
        $grantAccessStmt->execute();
        $grantAccessStmt->close();
        
        // Redirect to login page so they can login
        header("Location: login.php");
        exit;
    } else {
        header("Location: signup.php?error=1");
        exit;
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/eventhandler.js"></script>
</head>

<body>
    <div id="container">
        <header id="header-auth">
            <h1><span class="dish">Dish</span><span class="covery">covery</span></h1>
        </header>
        <main id="main-center">

            <form id="signupform" class="auth-form" action="signup.php" method="post" enctype="multipart/form-data">
                <h2 class="login-head">Sign up</h2>
                <div class="form-input-grid">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" />
                    &nbsp;
                    <div id="error-text-email" class="error-text hidden">
                        Email is invalid.
                    </div>

                    <label for="sname">Screen Name:</label>
                    <input type="text" id="sname" name="screenname" />
                    &nbsp;
                    <div id="error-text-sname" class="error-text hidden">
                        Screen Name is invalid.
                    </div>

                    <label for="pwd">Password:</label>
                    <input type="password" id="pwd" name="password" />
                    &nbsp;
                    <div id="error-text-password" class="error-text hidden">
                        password is invalid.
                    </div>

                    <label for="cpassword">Confirm Password:</label>
                    <input type="password" id="cpassword" name="cpassword" />
                    &nbsp;
                    <div id="error-text-cpassword" class="error-text hidden">
                        password doesnot match.
                    </div>

                    <label for="profilephoto">Profile Picture</label>
                    
                    <input type="file" id="profilephoto" name="avatar" accept="image/png, image/jpeg, image/gif" />
                    &nbsp;
                        <div id="error-text-profilephoto" class="error-text hidden">
                        profilephoto is invalid.
                    </div>
                </div>
                <div class="align-right">
                    <input type="submit" value="Sign Up" />
                </div>
                
            </form>
            <div class="form-note">
                    <p>Already have an account? <a href="login.php">Login.</a></p>
                </div>

        </main>
        <footer id="footer-auth">
            <p class="footer-text">CS 215: Assignment 3</p>
        </footer>
    </div>
    <script src="js/eventregistersignup.js"></script>
</body>

</html>