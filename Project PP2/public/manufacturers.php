<?php
// Connect to the database
$conn = new PDO('mysql:host=localhost;dbname=yourdbname', 'root', 'newpassword');
// Fetch all manufacturers and their average ratings
$manufacturers = $conn->query("
    SELECT manufacturer, AVG(subquery.avg_rating) as avg_manufacturer_rating
    FROM (
        SELECT manufacturer, AVG(rating) as avg_rating
        FROM items 
        JOIN reviews ON items.id = reviews.item_id
        GROUP BY items.id
    ) as subquery
    GROUP BY manufacturer
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manufacturers</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Manufacturers and Their Average Ratings</h1>
    <div class="list-group">
        <?php foreach ($manufacturers as $manufacturer): ?>
            <a href="items_by_manufacturer.php?manufacturer=<?= urlencode($manufacturer['manufacturer']) ?>" class="list-group-item list-group-item-action">
                <?= htmlspecialchars($manufacturer['manufacturer']) ?>
                <span class="badge badge-primary badge-pill float-right">Avg. Rating: <?= round($manufacturer['avg_manufacturer_rating'], 2) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
