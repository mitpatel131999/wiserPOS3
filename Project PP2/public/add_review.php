<?php
// Start the session
session_start();

// Connect to the database
$conn = new PDO('mysql:host=localhost;dbname=yourdbname', 'root', 'newpassword');

$item_id = $_POST['item_id'];
$reviewer_name = $_POST['reviewer_name'];
$rating = $_POST['rating'];
$review_text = $_POST['review_text'];

// Perform server-side validation
if (strlen($reviewer_name) > 2 && !preg_match('/[-_+"]/',$reviewer_name)) {
    // Save the reviewer's name in session
    $_SESSION['reviewer_name'] = $reviewer_name;
    
    // Sanitize and save to the database
    $stmt = $conn->prepare("INSERT INTO reviews (item_id, reviewer_name, rating, review_text) VALUES (?, ?, ?, ?)");
    $stmt->execute([$item_id, $reviewer_name, $rating, $review_text]);
} else {
    echo "Invalid input!";
}

// Redirect back to the review page
header("Location: review.php?id=" . $item_id);
?>
