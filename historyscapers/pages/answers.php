<?php
include '../config.php'; 
session_start(); 

$question_id = $_GET['question_id'] ?? 0;

$sql = "SELECT q.question_title, q.question_body, q.question_image_url, u.username
        FROM questions q
        JOIN users u ON q.user_id = u.user_id
        WHERE q.question_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $question_id);
$stmt->execute();
$question_result = $stmt->get_result();

$answers_sql = "SELECT a.answer_id, a.answer_body, a.answer_image_url, u.username,
                 (SELECT COUNT(*) FROM likes l WHERE l.post_id = a.answer_id AND l.post_type = 'answer' AND l.status = 1) AS like_count,
                 (SELECT COUNT(*) FROM likes l WHERE l.user_id = ? AND l.post_id = a.answer_id AND l.post_type = 'answer') AS user_like_status
                 FROM answers a
                 JOIN users u ON a.user_id = u.user_id
                 WHERE a.question_id = ?";
$answers_stmt = $conn->prepare($answers_sql);
$answers_stmt->bind_param("ii", $_SESSION['user_id'], $question_id);
$answers_stmt->execute();
$answers_result = $answers_stmt->get_result();

$user_id = $_SESSION['user_id']; 
$comments_sql = "SELECT c.comment_id, c.comment_body, u.username,
                 (SELECT COUNT(*) FROM likes l WHERE l.post_id = c.comment_id AND l.post_type = 'comment' AND l.status = 1) AS like_count,
                 (SELECT COUNT(*) FROM likes l WHERE l.user_id = ? AND l.post_id = c.comment_id AND l.post_type = 'comment') AS user_like_status
                 FROM comments c
                 JOIN users u ON c.user_id = u.user_id
                 WHERE c.parent_id = ?";
$comments_stmt = $conn->prepare($comments_sql);
$comments_stmt->bind_param("ii", $user_id, $question_id);
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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['like_type'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];
    $post_type = $_POST['like_type'];

    $check_like_sql = "SELECT * FROM likes WHERE user_id = ? AND post_id = ? AND post_type = ?";
    $check_like_stmt = $conn->prepare($check_like_sql);
    $check_like_stmt->bind_param("iis", $user_id, $post_id, $post_type);
    $check_like_stmt->execute();
    $check_like_result = $check_like_stmt->get_result();

    if ($check_like_result->num_rows > 0) {
        $update_like_sql = "UPDATE likes SET status = IF(status=1, 0, 1) WHERE user_id = ? AND post_id = ? AND post_type = ?";
        $update_like_stmt = $conn->prepare($update_like_sql);
        $update_like_stmt->bind_param("iis", $user_id, $post_id, $post_type);
        $update_like_stmt->execute();
    } else {
        $insert_like_sql = "INSERT INTO likes (user_id, post_id, post_type, status) VALUES (?, ?, ?, 1)";
        $insert_like_stmt = $conn->prepare($insert_like_sql);
        $insert_like_stmt->bind_param("iis", $user_id, $post_id, $post_type);
        $insert_like_stmt->execute();
    }
    echo json_encode(["success" => true]);
    exit();
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <?php if ($question_result->num_rows > 0) {
            $question_row = $question_result->fetch_assoc();
            echo "<div class='question'>";
            echo "<div class='post-header'>";
            echo "<span class='user-name'>" . htmlspecialchars($question_row['username']) . "</span>";
            echo "</div>";
            echo "<p class='title'>" . htmlspecialchars($question_row['question_title']) . "</p>";
            echo "<p>" . htmlspecialchars($question_row['question_body']) . "</p>";
            if ($question_row['question_image_url']) {
                echo "<div class='photo'><img src='" . htmlspecialchars($question_row['question_image_url']) . "' alt='Question Image'></div>";
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
                    echo "<div class='photo'><img src='" . htmlspecialchars($answer_row['answer_image_url']) . "' alt='Answer Image'></div>";
                }

                echo "<img src='" . ($answer_row['user_like_status'] > 0 ? '../images/like.svg' : '../images/notlike.svg') . "' alt='Like Button' class='like-btn' data-post-id='" . $answer_row['answer_id'] . "' data-like-type='answer'>";

                echo "<span class='like-count' id='like-count-" . $answer_row['answer_id'] . "'>" . $answer_row['like_count'] . "</span>";
                echo "</div>";
                
            }
        
            
        } else {
            echo "<p>No answers yet.</p>";
        }
        ?>
        </br>
        <h2>Post an Answer:</h2>
        <div class="postawnser">
        <div class="answer-section">
        <form action="answers.php?question_id=<?php echo $question_id; ?>" method="post" enctype="multipart/form-data">
            <label for="answer_body">Your Answer:</label>
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
        </div>
        </br>
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
                echo "<img src='" . ($comment['user_like_status'] > 0 ? '../images/like.svg' : '../images/notlike.svg') . "' alt='Like Button' class='like-btn' data-post-id='" . $comment['comment_id'] . "' data-like-type='comment'>";
                echo "<span class='like-count' id='like-count-" . $comment['comment_id'] . "'>" . $comment['like_count'] . "</span>";
                echo "</div>";
            }            
            
        } else {
            echo "<p>No comments found.</p>";
        }
        ?>
    </main>
   
    <script>
  $(document).on('click', '.like-btn', function() {
    var postId = $(this).data('post-id');
    var likeType = $(this).data('like-type');
    var img = $(this);
    var countSpan = $('#like-count-' + postId);

    $.post('answers.php?question_id=<?php echo $question_id; ?>', {
        post_id: postId,
        like_type: likeType
    }, function(response) {
        response = JSON.parse(response);
        if (response.success) {
            
            if (img.attr('src').includes('notlike.svg')) {
                img.attr('src', '../images/like.svg');
                countSpan.text(parseInt(countSpan.text()) + 1); 
            } else {
                img.attr('src', '../images/notlike.svg');
                countSpan.text(parseInt(countSpan.text()) - 1);
            }
        }
    });
});



    </script>
</body>
</html>
