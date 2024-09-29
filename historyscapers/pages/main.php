<?php
include '../config.php'; 
session_start();

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$searchTerm = $conn->real_escape_string($searchTerm);

// Only show active posts for regular users; admins can see all posts
if ($_SESSION['role'] == 'admin') {
    $sql = "SELECT q.question_id, q.question_title, q.question_body, q.question_image_url, q.status, u.username, q.user_id
            FROM questions q
            JOIN users u ON q.user_id = u.user_id
            WHERE (q.question_title LIKE '%$searchTerm%' OR q.question_body LIKE '%$searchTerm%')
            ORDER BY q.question_id DESC";
} else {
    $sql = "SELECT q.question_id, q.question_title, q.question_body, q.question_image_url, q.status, u.username, q.user_id
            FROM questions q
            JOIN users u ON q.user_id = u.user_id
            WHERE (q.question_title LIKE '%$searchTerm%' OR q.question_body LIKE '%$searchTerm%')
            AND q.status = 'active'
            ORDER BY q.question_id DESC";
}
$result = $conn->query($sql);

if (!$result) {
    echo "Error in SQL query: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Feed</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&family=Sofia&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <form method="GET" action="">
                <div class="search-bar">
                    <input type="text" name="search" placeholder="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit">🔍</button>
                </div>
            </form>
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
        <h1>Main Feed</h1>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $question_user_id = isset($row['user_id']) ? $row['user_id'] : null;

                // Ensure 'status' exists before using it
                if (isset($row['status']) && $row['status'] == 'pending_deletion' && $_SESSION['role'] == 'admin') {
                    echo "<div class='post pending'>";
                    echo "<span class='status'>Pending Deletion</span>";
                } else {
                    echo "<div class='post'>";
                }

                echo "<div class='post-header'>";
                echo "<span class='user-name'>" . htmlspecialchars($row['username']) . "</span>";
                echo "</div>";
                echo "<p class='title'><a href='answers.php?question_id=" . htmlspecialchars($row['question_id']) . "'>" . htmlspecialchars($row['question_title']) . "</a></p>";
                echo "<p>" . htmlspecialchars($row['question_body']) . "</p>";
                if ($row['question_image_url']) {
                    echo "<div class='photo'><img src='" . htmlspecialchars($row['question_image_url']) . "' alt='Post Image'></div>";
                }

                // Admin can edit/delete all posts, regular users can edit/delete only their own posts
                if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $question_user_id || $_SESSION['role'] == 'admin')) {
                    echo "<form action='delete_question.php' method='post' style='display:inline;'>";
                    echo "<input type='hidden' name='question_id' value='" . htmlspecialchars($row['question_id']) . "'>";
                    if ($_SESSION['role'] == 'admin' || $row['status'] == 'active') {
                        echo "<span class='delete-button'><button type='submit' class='delete-button' onclick='return confirm(\"Are you sure you want to delete this question?\")'>Delete</button></span>";
                    }
                    echo "</form>";

                    echo "<span class='edit-button'><a href='edit_question.php?question_id=" . htmlspecialchars($row['question_id']) . "'>Edit</a></span>";
                }
                
                echo "</div>";
            }
        } else {
            echo "<p>No questions found.</p>";
        }
        $conn->close();
        ?>
    </main>
</body>
</html>
