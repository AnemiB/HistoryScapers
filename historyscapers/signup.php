<?php  
session_start(); 

require 'config.php'; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {

 
    $username = $_POST['username'];
    $confirm_username = $_POST['confirm_username'];
    $email = $_POST['email'];
    $confirm_email = $_POST['confirm_email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    
    if ($username != $confirm_username) {
        echo "Usernames do not match.";
    } elseif ($email != $confirm_email) {
        echo "Emails do not match.";
    } elseif ($password != $confirm_password) {
        echo "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        if ($stmt->execute()) {
            echo "Registration complete!";
            header("Location: index.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - History Scapers</title>
    <link rel="stylesheet" href="css/signup.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&family=Sofia&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('images/history.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
    </style>
</head>
<body>
    <div class="signup-container">
        <form class="signup-form" action="signup.php" method="post">
            <h2>Welcome to History Scapers!</h2>
            <h3>Sign Up</h3>
            
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>

            <label for="confirm-username">Confirm Username</label>
            <input type="text" id="confirm-username" name="confirm_username" placeholder="Confirm your username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="confirm-email">Confirm Email</label>
            <input type="email" id="confirm-email" name="confirm_email" placeholder="Confirm your email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm your password" required>

            <button type="submit">Sign Up</button>
        </form>
        <a href="index.php" class="signup-link">To Log In</a>
    </div>
</body>
</html>
