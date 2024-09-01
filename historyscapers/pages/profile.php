<?php
include '../config.php'; // Include your database connection
session_start(); // Start the session to access session variables

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch current user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Handle form submission for updating username, email, and password
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['new_username'];
    $new_email = $_POST['new_email'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // Validate inputs
    $update_needed = false;

    if (!empty($new_username)) {
        $update_needed = true;
        $update_sql = "UPDATE users SET username = ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $new_username, $user_id);
        $update_stmt->execute();
        $update_stmt->close();
    }

    if (!empty($new_email)) {
        $update_needed = true;
        $update_sql = "UPDATE users SET email = ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $new_email, $user_id);
        $update_stmt->execute();
        $update_stmt->close();
    }

    if (!empty($old_password) && !empty($new_password)) {
        // Verify old password
        $sql = "SELECT password FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $password_result = $stmt->get_result();
        $user_data = $password_result->fetch_assoc();
        $stored_password = $user_data['password'];

        if (password_verify($old_password, $stored_password)) {
            $update_needed = true;
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $update_sql = "UPDATE users SET password = ? WHERE user_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $hashed_password, $user_id);
            $update_stmt->execute();
            $update_stmt->close();
        } else {
            echo "<script>alert('Old password is incorrect.');</script>";
        }
    }

    if ($update_needed) {
        echo "<script>alert('Profile updated successfully.'); window.location.href = 'profile.php';</script>";
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../css/profile.css">
</head>
<body>
<header>
        <nav>
            <div class="menu-icon">☰</div>
            <div class="search-bar">
                <input type="text" placeholder="Search">
                <button>🔍</button>
            </div>
            <div class="logo">
                <img src="../images/logo.png" alt="Logo" height="50px">
            </div>
            <ul>
                <li><a href="../pages/main.php">Main Feed</a></li>
                <li><a href="../pages/profile.php">Profile</a></li>
                <li><a href="../pages/createpost.php">Create Post</a></li>
                <li><a href="../logout.php">Log Out</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Profile</h1>
        <form action="profile.php" method="post">
            <label for="old_username">Old Username:</label>
            <input type="text" id="old_username" name="old_username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
            
            <label for="new_username">New Username:</label>
            <input type="text" id="new_username" name="new_username" placeholder="Enter new username">
            
            <label for="old_email">Old Email:</label>
            <input type="email" id="old_email" name="old_email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
            
            <label for="new_email">New Email:</label>
            <input type="email" id="new_email" name="new_email" placeholder="Enter new email">

            <label for="old_password">Old Password:</label>
            <input type="password" id="old_password" name="old_password" placeholder="Enter old password">

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" placeholder="Enter new password">

            <button type="submit">Update Profile</button>
        </form>
    </main>
</body>
</html>
