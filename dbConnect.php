<?php
$servername = "localhost";
$username = "id9423447_team5";
$password = "team123!@#";
$db = "id9423447_team5";

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>