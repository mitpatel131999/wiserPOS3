<?php
// Start the session
session_start();

// Connect to the database
$conn = new PDO('mysql:host=localhost;dbname=yourdbname', 'root', 'newpassword');

// Determine the sorting order
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'number_of_reviews';
$order = isset($_GET['order']) ? $_GET['order'] : 'desc';

// Fetch all items with sorting
$items = $conn->query("
    SELECT items.id, items.name, items.manufacturer, COUNT(reviews.id) as number_of_reviews, AVG(reviews.rating) as average_rating
    FROM items
    LEFT JOIN reviews ON items.id = reviews.item_id
    GROUP BY items.id
    ORDER BY $sort_by $order
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Item Reviews</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Item List</h1>
    <div class="mb-3">
        <a href="add_edit_item.php" class="btn btn-success">Add New Item</a>
        <a href="?sort_by=number_of_reviews&order=asc" class="btn btn-primary">Sort by Reviews (Asc)</a>
        <a href="?sort_by=number_of_reviews&order=desc" class="btn btn-primary">Sort by Reviews (Desc)</a>
        <a href="?sort_by=average_rating&order=asc" class="btn btn-primary">Sort by Rating (Asc)</a>
        <a href="?sort_by=average_rating&order=desc" class="btn btn-primary">Sort by Rating (Desc)</a>
    </div>
    <div class="list-group">
        <?php foreach ($items as $item): ?>
            <a href="review.php?id=<?= $item['id'] ?>" class="list-group-item list-group-item-action">
                <?= htmlspecialchars($item['name']) ?> - <small><?= htmlspecialchars($item['manufacturer']) ?></small>
                <span class="badge badge-primary badge-pill float-right">Reviews: <?= $item['number_of_reviews'] ?>, Avg. Rating: <?= round($item['average_rating'], 2) ?></span>
                <a href="delete_item.php?id=<?= $item['id'] ?>" class="btn btn-danger btn-sm float-right ml-2">Delete</a>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
