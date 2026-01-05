<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);

    $stmt = $mysqli->prepare(
        "SELECT UserID, ScreenName, AvatarURL
         FROM User
         WHERE Email = ? AND Password = ?"
    );
    $stmt->bind_param("ss", $email, $pass);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $_SESSION['UserID'] = $row['UserID'];
        $_SESSION['ScreenName'] = $row['ScreenName'];
        $_SESSION['AvatarURL'] = $row['AvatarURL'];

        header("Location: recipe-page.php");
        exit;
    }

    header("Location: login.php?error=1");
    exit;
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <link rel="stylesheet" href="css/style.css">
    <script src="js/eventhandler.js"></script>
</head>

<body>
    <div id="container">
        <header id="header-auth">
            <h1><span class="dish">Dish</span><span class="covery">covery</span></h1>
        </header>
        <main id="main-center">


            <form id="form-auth" class="auth-form" action="login.php" method="post">

                <h2 class="login-head">Login</h2>
                <div class="form-input-grid">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" />
                    &nbsp; 
                    <!-- Example: A <div> tag is added here with an id attribute 
                         that labels it as the place to display a username error message 
                         and provides appropriate styles for error messages-->
                    <div id="error-text-email" class="error-text hidden">
                        Email is invalid
                    </div> 


                    <label for="password">Password</label>
                    <input type="password" id="pwd" name="password" />
                    &nbsp; 
                    <!-- To Do 3: Add text and attributes to this div so it can show password error messages -->
                   
                    <div id="error-text-password" class="error-text hidden">
                        Password is invalid
                    </div>

                </div>
                <div class="align-right">

                    <input type="submit" value="Login" />
                </div>
                
            </form>
            <div class="form-note">

                    <p>Don't have an account?<a href="signup.php">Signup.</a></p>
                </div>

        </main>
        <footer id="footer-auth">

            <p class="footer-text">CS 215: Assignment 3</p>

        </footer>
    </div>
    <script src="js/eventregisterlogin.js"></script>
</body>

</html>