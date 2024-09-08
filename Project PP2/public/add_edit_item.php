<?php
// Start the session
session_start();

// Connect to the database
$conn = new PDO('mysql:host=localhost;dbname=yourdbname', 'root', 'newpassword');

// Check if it's editing mode
$item_id = isset($_GET['id']) ? $_GET['id'] : null;
$item_details = null;

if ($item_id) {
    $item = $conn->prepare("SELECT * FROM items WHERE id = ?");
    $item->execute([$item_id]);
    $item_details = $item->fetch();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $manufacturer = $_POST['manufacturer'];

    if ($item_id) {
        // Update item
        $stmt = $conn->prepare("UPDATE items SET name = ?, manufacturer = ? WHERE id = ?");
        $stmt->execute([$name, $manufacturer, $item_id]);
    } else {
        // Insert new item
        $stmt = $conn->prepare("INSERT INTO items (name, manufacturer) VALUES (?, ?)");
        $stmt->execute([$name, $manufacturer]);
    }

    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $item_id ? 'Edit Item' : 'Add Item' ?></title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4"><?= $item_id ? 'Edit Item' : 'Add Item' ?></h1>
    <form method="post" class="mb-3">
        <div class="form-group">
            <label for="name">Item Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($item_details['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="manufacturer">Manufacturer</label>
            <input type="text" name="manufacturer" class="form-control" value="<?= htmlspecialchars($item_details['manufacturer'] ?? '') ?>" required>
        </div>
        <button type="submit" class="btn btn-success"><?= $item_id ? 'Update' : 'Add' ?> Item</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
