<?php
// Connect to the database
$conn = new PDO('mysql:host=localhost;dbname=yourdbname', 'root', 'newpassword');

// Get the manufacturer from the URL
$manufacturer = $_GET['manufacturer'];

// Fetch all items from the specified manufacturer and their average ratings
$items = $conn->prepare("
    SELECT items.id, items.name, AVG(reviews.rating) as avg_rating
    FROM items 
    LEFT JOIN reviews ON items.id = reviews.item_id
    WHERE manufacturer = ?
    GROUP BY items.id
");
$items->execute([$manufacturer]);
$items_list = $items->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items by Manufacturer: <?= htmlspecialchars($manufacturer) ?></title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Items by <?= htmlspecialchars($manufacturer) ?></h1>
    <div class="list-group">
        <?php foreach ($items_list as $item): ?>
            <a href="review.php?id=<?= $item['id'] ?>" class="list-group-item list-group-item-action">
                <?= htmlspecialchars($item['name']) ?>
                <span class="badge badge-primary badge-pill float-right">Avg. Rating: <?= round($item['avg_rating'], 2) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
