<?php
include '../config.php'; // Include your database connection
session_start(); // Start the session to access session variables

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Get the question ID from POST request
$question_id = $_POST['question_id'] ?? 0;

// Ensure the user is authorized to delete the question
$sql = "SELECT user_id FROM questions WHERE question_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $question_id);
$stmt->execute();
$result = $stmt->get_result();
$question = $result->fetch_assoc();

// Check if the user owns the post or if the user is an admin
if ($question && ($question['user_id'] == $_SESSION['user_id'] || $_SESSION['role'] == 'admin')) {
    // For regular users, set the question status to 'pending_deletion'
    if ($_SESSION['role'] != 'admin') {
        $update_status_sql = "UPDATE questions SET status = 'pending_deletion' WHERE question_id = ?";
        $update_status_stmt = $conn->prepare($update_status_sql);
        $update_status_stmt->bind_param("i", $question_id);
        $update_status_stmt->execute();
    } else {
        // For admins, permanently delete the post
        // Delete comments associated with the question
        $delete_comments_sql = "DELETE FROM comments WHERE parent_id = ? AND parent_type = 'question'";
        $delete_comments_stmt = $conn->prepare($delete_comments_sql);
        $delete_comments_stmt->bind_param("i", $question_id);
        $delete_comments_stmt->execute();
        
        // Delete answers associated with the question
        $delete_answers_sql = "DELETE FROM answers WHERE question_id = ?";
        $delete_answers_stmt = $conn->prepare($delete_answers_sql);
        $delete_answers_stmt->bind_param("i", $question_id);
        $delete_answers_stmt->execute();
        
        // Delete the question itself
        $delete_question_sql = "DELETE FROM questions WHERE question_id = ?";
        $delete_question_stmt = $conn->prepare($delete_question_sql);
        $delete_question_stmt->bind_param("i", $question_id);
        $delete_question_stmt->execute();
    }
    
    // Redirect back to the main feed
    header('Location: main.php');
    exit();
} else {
    echo "You are not authorized to delete this question.";
}

$conn->close();
?>
