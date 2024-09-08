<?php
// Connect to the database
$conn = new PDO('mysql:host=localhost;dbname=yourdbname', 'root', 'newpassword');

// Get the item ID to delete
$item_id = $_GET['id'];

// Delete the item and associated reviews
$stmt = $conn->prepare("DELETE FROM reviews WHERE item_id = ?");
$stmt->execute([$item_id]);

$stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
$stmt->execute([$item_id]);

header('Location: index.php');
exit;
?>
