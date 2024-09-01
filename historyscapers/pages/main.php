<?php
include '../config.php';
session_start(); 

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$searchTerm = $conn->real_escape_string($searchTerm);

$sql = "SELECT q.question_id, q.question_title, q.question_body, q.question_image_url, u.username, q.user_id
        FROM questions q
        JOIN users u ON q.user_id = u.user_id
        WHERE q.question_title LIKE '%$searchTerm%' OR q.question_body LIKE '%$searchTerm%'
        ORDER BY q.question_id DESC";
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
</head>
<body>
<style>
.user-name {
    font-weight: bold;
    font-size: 1.5rem !important;
}

a {
    color: #ffffff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

img {
    max-width: 80%;
    border-radius: 8px;
}

.delete-button {
    background-color: #f44336; 
    color: #fff; 
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.delete-button:hover {
    background-color: #d32f2f; 
}


h1 {
    text-align: center;
  }

.centered-image {
    display: block;
    margin: 0 auto;
    border-radius: 8px;
    margin-top: 10px;
    margin-left: 10%;
    margin-bottom: 1%;
}
</style>
    <header>
        <nav>
            <div class="menu-icon">☰</div>
            <form method="GET" action="">
                <div class="search-bar">
                    <input type="text" name="search" placeholder="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit">🔍</button>
                </div>
            </form>
            <div class="logo">
                <img src="../images/logo.png" alt="Logo" height="50px" width="70px">
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
                
                echo "<div class='post'>";
                echo "<div class='post-header'>";
                echo "<span class='user-name'>" . htmlspecialchars($row['username']) . "</span>";
                echo "</div>";
                echo "<p class='title'><a href='answers.php?question_id=" . htmlspecialchars($row['question_id']) . "'>" . htmlspecialchars($row['question_title']) . "</a></p>";
                echo "<p>" . htmlspecialchars($row['question_body']) . "</p>";
                if ($row['question_image_url']) {
                    echo "<img src='" . htmlspecialchars($row['question_image_url']) . "' alt='Post Image' class='centered-image' height='300px' width='90%'>";
                }
                if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $question_user_id) {
                    echo "<form action='delete_question.php' method='post' style='display:inline;'>";
                    echo "<input type='hidden' name='question_id' value='" . htmlspecialchars($row['question_id']) . "'>";
                    echo "<span class='delete-button'><button type='submit' class='delete-button' onclick='return confirm(\"Are you sure you want to delete this question?\")'>Delete</button></span>";
                    echo "</form>";
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
