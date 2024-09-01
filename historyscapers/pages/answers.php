<?php
include '../config.php'; 
session_start(); 
$question_id = $_GET['question_id'] ?? 0;

// Fetch the question details
$sql = "SELECT q.question_title, q.question_body, q.question_image_url, u.username
        FROM questions q
        JOIN users u ON q.user_id = u.user_id
        WHERE q.question_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $question_id);
$stmt->execute();
$question_result = $stmt->get_result();

// Fetch the answers to the question
$answers_sql = "SELECT a.answer_id, a.answer_body, a.answer_image_url, u.username
                FROM answers a
                JOIN users u ON a.user_id = u.user_id
                WHERE a.question_id = ?";
$answers_stmt = $conn->prepare($answers_sql);
$answers_stmt->bind_param("i", $question_id);
$answers_stmt->execute();
$answers_result = $answers_stmt->get_result();

// Fetch comments for the question
$comments_sql = "SELECT c.comment_id, c.comment_body, u.username
                 FROM comments c
                 JOIN users u ON c.user_id = u.user_id
                 WHERE c.parent_id = ?";
$comments_stmt = $conn->prepare($comments_sql);
$comments_stmt->bind_param("i", $question_id);
$comments_stmt->execute();
$comments_result = $comments_stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['answer_body'])) {
    $answer_body = $_POST['answer_body'];
    $answer_image_url = null;

    if (!empty($_FILES["answer_image"]["name"])) {
        $target_dir = "../uploads/"; 
        $target_file = $target_dir . basename($_FILES["answer_image"]["name"]);
        if (move_uploaded_file($_FILES["answer_image"]["tmp_name"], $target_file)) {
            $answer_image_url = $target_file; 
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        }
    }
    $insert_sql = "INSERT INTO answers (question_id, user_id, answer_body, answer_image_url) VALUES (?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iiss", $question_id, $_SESSION['user_id'], $answer_body, $answer_image_url);
    if ($insert_stmt->execute()) {
        echo "<script>alert('Answer posted successfully'); window.location.href = 'answers.php?question_id=$question_id';</script>";
    } else {
        echo "Error: " . $insert_stmt->error;
    }
    $insert_stmt->close();
}

// Handle comment form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_body'])) {
    $parent_id = $_POST['parent_id'];
    $comment_body = $_POST['comment_body'];
    $user_id = $_SESSION['user_id'];

    $check_sql = "SELECT question_id FROM questions WHERE question_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $parent_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $insert_comment_sql = "INSERT INTO comments (parent_id, user_id, comment_body) VALUES (?, ?, ?)";
        $insert_comment_stmt = $conn->prepare($insert_comment_sql);
        $insert_comment_stmt->bind_param("iis", $parent_id, $user_id, $comment_body);

        if ($insert_comment_stmt->execute()) {
            header("Location: answers.php?question_id=" . $question_id);
            exit();
        } else {
            echo "Error: " . $insert_comment_stmt->error;
        }
        $insert_comment_stmt->close();
    } else {
        echo "Invalid parent ID.";
    }

    $check_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Answer Page</title>
    <link rel="stylesheet" href="../css/answers.css">
    <style>
        .user-name {
        font-weight: bold;
        font-size: 1.2rem !important;
         } 

        .like-btn {
            cursor: pointer;
            width: 24px;
            height: 24px;
        }
        .comment-section {
            margin-top: 20px;
        }
        .answer-section textarea {
          flex: 1;
          padding: 10px;
          border-radius: 15px;
          border: none;
          margin-right: 10px;
          background-color: #fff;
          width: 25%;
        }

        .answer-section button {
          padding: 10px 20px;
          background-color: #f8b7c0;
          border: none;
          border-radius: 25px;
         cursor: pointer;
        }
    </style>
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
        <?php if ($question_result->num_rows > 0) {
            $question_row = $question_result->fetch_assoc();
            echo "<div class='question'>";
            echo "<div class='post-header'>";
            echo "<span class='user-name'>" . htmlspecialchars($question_row['username']) . "</span>";
            echo "</div>";
            echo "<p class='title'>" . htmlspecialchars($question_row['question_title']) . "</p>";
            echo "<p>" . htmlspecialchars($question_row['question_body']) . "</p>";
            if ($question_row['question_image_url']) {
                echo "<img src='" . htmlspecialchars($question_row['question_image_url']) . "' alt='Question Image' class='centered-image' height='300px' width='90%'>";
            }
            echo "</div>";
        } else {
            echo "<p>Question not found.</p>";
        }
        ?>
        <h1>Answers:</h1>
        <?php if ($answers_result->num_rows > 0) {
            while($answer_row = $answers_result->fetch_assoc()) {
                echo "<div class='answer'>";
                echo "<div class='user'>";
                echo "<span class='user-name'>" . htmlspecialchars($answer_row['username']) . "</span>";
                echo "</div>";
                echo "<p>" . htmlspecialchars($answer_row['answer_body']) . "</p>";
                if ($answer_row['answer_image_url']) {
                    echo "<img src='" . htmlspecialchars($answer_row['answer_image_url']) . "' alt='Answer Image' class='centered-image' height='300px' width='90%'>";
                }
                echo "<img src='../images/notlike.svg' alt='Like Button' class='like-btn' onclick='toggleLike(this)'>";
                echo "</div>";
            }
        } else {
            echo "<p>No answers yet.</p>";
        }
        ?>
        <h2>Post an Answer</h2>
        <div class="answer-section">
        <form action="answers.php?question_id=<?php echo $question_id; ?>" method="post" enctype="multipart/form-data">
            <label for="answer_body">Your Answer:</label>
            </br>
            <textarea id="answer_body" name="answer_body" placeholder="Write your answer here" required></textarea>
         </br>
         </br>
            <label for="answer_image">Upload Image:</label>
            </br>   
            
            <input type="file" id="answer_image" name="answer_image">
        </br>
        </br>
            <button type="submit">Post Answer</button>
        </form>
        </div>
        <h2>Comments:</h2>
        <div class="comment-section">
            <form action="answers.php?question_id=<?php echo $question_id; ?>" method="post">
                <input type="hidden" name="parent_id" value="<?php echo htmlspecialchars($question_id); ?>">
                <input type="text" name="comment_body" placeholder="Comment" required>
                <button type="submit">Post</button>
            </form>
        </div>
        </br>
        <?php
        if ($comments_result && $comments_result->num_rows > 0) {
            while ($comment = $comments_result->fetch_assoc()) {
                echo "<div class='comment'>";
                echo "<div class='user'>";
                echo "<span class='user-name'>" . htmlspecialchars($comment['username']) . "</span>";
                echo "</div>";
                echo "<p>" . htmlspecialchars($comment['comment_body']) . "</p>";
                echo "<img src='../images/notlike.svg' alt='Like Button' class='like-btn' onclick='toggleLike(this)'>";
                echo "</div>";
            }
        } else {
            echo "<p>No comments found.</p>";
        }
        ?>
    </main>
    <script>
        function toggleLike(img) {
            if (img.src.includes('notlike.svg')) {
                img.src = '../images/like.svg';
            } else {
                img.src = '../images/notlike.svg';
            }
        }
    </script>
</body>
</html>
