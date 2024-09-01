<?php
include '../config.php'; 
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit();
}

$question_id = $_POST['question_id'] ?? 0;

// Ensure the user is the question owner to delete the question
$sql = "SELECT user_id FROM questions WHERE question_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $question_id);
$stmt->execute();
$result = $stmt->get_result();
$question = $result->fetch_assoc();

if ($question && $question['user_id'] == $_SESSION['user_id']) {

    $delete_comments_sql = "DELETE FROM comments WHERE parent_id = ? AND parent_type = 'question'";
    $delete_comments_stmt = $conn->prepare($delete_comments_sql);
    $delete_comments_stmt->bind_param("i", $question_id);
    $delete_comments_stmt->execute();
    
    $delete_answers_sql = "DELETE FROM answers WHERE question_id = ?";
    $delete_answers_stmt = $conn->prepare($delete_answers_sql);
    $delete_answers_stmt->bind_param("i", $question_id);
    $delete_answers_stmt->execute();
    
    $delete_question_sql = "DELETE FROM questions WHERE question_id = ?";
    $delete_question_stmt = $conn->prepare($delete_question_sql);
    $delete_question_stmt->bind_param("i", $question_id);
    $delete_question_stmt->execute();
    
    header('Location: main.php');
    exit();
} else {
    echo "You are not authorized to delete this question.";
}

$conn->close();
?>
