<?php  
session_start(); 

require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $username = $_POST['username']; 
    $password = $_POST['password']; 
    
    $sql = "SELECT * FROM users WHERE username = ?"; 
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $username);
    $stmt->execute(); 
    $result = $stmt->get_result(); 
    if ($result->num_rows > 0) { 
        $user = $result->fetch_assoc(); 
        if (password_verify($password, $user['password'])) { 
            $_SESSION['user_id'] = $user['user_id'];
            header("Location: pages/main.php"); 
            exit(); 
        } else { 
            echo "Invalid username or password"; 
        } 
    } else { 
        echo "Invalid username or password"; 
    } 
    $stmt->close(); 

    $conn->close();  
} 
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - History Scapers</title>
    <link rel="stylesheet" href="css/login.css">
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
    <div class="login-container">
        <form class="login-form" action="index.php" method="post">
            <h2>Welcome Back to History Scapers!</h2>
            <h3>Log In</h3>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
        
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
        
            <button type="submit">Log In</button>
        </form>

        <a href="signup.php" class="signup-link">To Sign Up</a>
    </div>
</body>
</html>
