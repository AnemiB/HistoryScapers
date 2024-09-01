<?php 
session_start(); 

session_destroy(); 

header("Location: ../index.php"); 
exit(); // Add this line to stop any further execution
?>