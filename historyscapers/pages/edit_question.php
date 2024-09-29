<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You need to log in to edit this post.'); window.location.href = '../index.php';</script>";
    exit();
}

$question_id = isset($_GET['question_id']) ? intval($_GET['question_id']) : 0;

if ($question_id > 0) {
    $sql = "SELECT question_title, question_body, question_image_url FROM questions WHERE question_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $question_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $question = $result->fetch_assoc();

    if (!$question) {
        echo "Post not found or you're not authorized to edit it.";
        exit();
    }

    // Handle form submission to update the question
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $question_title = $_POST['title'];
        $question_body = $_POST['body'];
        $question_image_url = $question['question_image_url'];

        if (!empty($_FILES["image"]["name"])) {
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $question_image_url = $target_file;
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
        }

        $sql = "UPDATE questions SET question_title = ?, question_body = ?, question_image_url = ? WHERE question_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $question_title, $question_body, $question_image_url, $question_id, $_SESSION['user_id']);

        if ($stmt->execute()) {
            echo "<script>alert('Post updated successfully'); window.location.href = '../pages/main.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
        exit();
    }
} else {
    echo "Invalid post ID.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="../css/createpost.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&family=Sofia&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <div class="search-bar">
                <input type="text" placeholder="Search">
                <button>🔍</button>
            </div>
            <div class="logo">
                <img src="../images/logo.png" alt="Logo" height="50px" width="60px">
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
        <h1>Edit Your Post</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="title">Headline</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($question['question_title']); ?>" required>
            
            <label for="body">Body</label>
            <textarea id="body" name="body" required><?php echo htmlspecialchars($question['question_body']); ?></textarea>
            
            <label for="image">Upload New Image (optional)</label>
            <input type="file" id="image" name="image">
            <p>Current Image: <?php echo $question['question_image_url'] ? "<img src='" . htmlspecialchars($question['question_image_url']) . "' alt='Post Image' height='100px'>" : "No image uploaded."; ?></p>
            
            <button type="submit" class="post">Save Changes</button>
        </form>
    </main>
</body>
</html>
