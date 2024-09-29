<?php  
session_start(); // Start the session 

require 'config.php'; // Include the config file for database connection

// Check if the form has been submitted 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    // Get the username and password from the form 
    $username = $_POST['username']; 
    $password = $_POST['password']; 
    
    // SQL query to find the user by username, including their role
    $sql = "SELECT * FROM users WHERE username = ?"; 
    
    // Prepare the SQL statement 
    $stmt = $conn->prepare($sql); 
    
    // Bind the username to the SQL statement 
    $stmt->bind_param("s", $username); // "s" means a string parameter 
    
    // Execute the SQL statement 
    $stmt->execute(); 
    
    // Store the result in the 'result' variable 
    $result = $stmt->get_result(); 
    
    // Check if user exists 
    if ($result->num_rows > 0) { 
        // Fetch user data 
        $user = $result->fetch_assoc(); 
        
        // Verify the password using password_verify()
        if (password_verify($password, $user['password'])) { 
            // Store user information in session 
            $_SESSION['user_id'] = $user['user_id']; // Store user_id for session-based functionality
            $_SESSION['role'] = $user['role']; // Store the user's role (admin or user) in session
            
            // Redirect to the main page
            header("Location: pages/main.php"); 
            exit(); // Terminate the script to ensure redirection 
        } else { 
            echo "Invalid username or password"; // Incorrect password
        } 
    } else { 
        echo "Invalid username or password"; // Username not found
    } 
    
    // Close the statement 
    $stmt->close(); 
    
    // Close the database connection 
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
