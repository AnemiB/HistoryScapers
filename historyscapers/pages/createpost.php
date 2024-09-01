<?php
session_start();

require '../config.php'; 
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You need to log in to create a post.'); window.location.href = '../index.php';</script>";
    exit();
}

// Form submission and post creation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; 
    $question_title = $_POST['title']; 
    $question_body = $_POST['body'];
    $question_image_url = null; 

    // Optional image upload
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "../uploads/"; 
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $question_image_url = $target_file; 
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        }
    }
    $sql = "INSERT INTO questions (user_id, question_title, question_body, question_image_url) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo "Error in preparing statement: " . $conn->error;
        exit();
    }
    
    $stmt->bind_param("isss", $user_id, $question_title, $question_body, $question_image_url);
    
    if ($stmt->execute()) {
        echo "<script>alert('New post created successfully'); window.location.href = '../pages/main.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
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
    <title>Create Post</title>
    <link rel="stylesheet" href="../css/createpost.css">
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
        <h1>New Post</h1>
        <form action="createpost.php" method="post" enctype="multipart/form-data">
            <label for="title">Headline</label>
            <input type="text" id="title" name="title" placeholder="Text" required>
            
            <label for="body">Body</label>
            <textarea id="body" name="body" placeholder="Text" required></textarea>
            
            <label for="image">Upload Image</label>
            <input type="file" id="image" name="image">
            </br>
            <button type="submit" class="post" border-radius="25px">Post</button>
        </form>
    </main>
</body>
</html>
