<?php
// Start the session
session_start();

// Connect to the database
$conn = new PDO('mysql:host=localhost;dbname=yourdbname', 'root', 'newpassword');

// Fetch item details and reviews
$item_id = $_GET['id'];
$item = $conn->prepare("SELECT * FROM items WHERE id = ?");
$item->execute([$item_id]);
$item_details = $item->fetch();

$reviews = $conn->prepare("SELECT * FROM reviews WHERE item_id = ?");
$reviews->execute([$item_id]);
$reviews_list = $reviews->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews for <?= htmlspecialchars($item_details['name']) ?></title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4"><?= htmlspecialchars($item_details['name']) ?></h1>
    
    <!-- Reviews Section -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Reviews</h5>
            <?php if (count($reviews_list) > 0): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($reviews_list as $review): ?>
                        <li class="list-group-item">
                            <strong><?= htmlspecialchars($review['reviewer_name']) ?></strong>:
                            <?= htmlspecialchars($review['review_text']) ?>
                            <span class="badge badge-primary badge-pill">Rating: <?= $review['rating'] ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="alert alert-info" role="alert">No reviews yet. Be the first to review!</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add a Review Button to Open Modal -->
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addReviewModal">
        Add a Review
    </button>

    <!-- Bootstrap Modal for Adding a Review -->
    <div class="modal fade" id="addReviewModal" tabindex="-1" role="dialog" aria-labelledby="addReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addReviewModalLabel">Add a Review</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="reviewForm" action="add_review.php" method="post">
                        <input type="hidden" name="item_id" value="<?= $item_id ?>">
                        <div class="form-group">
                            <input type="text" name="reviewer_name" class="form-control" placeholder="Your Name" value="<?= $_SESSION['reviewer_name'] ?? '' ?>">
                        </div>
                        <div class="form-group">
                            <input type="number" name="rating" min="1" max="5" class="form-control" placeholder="Rating" required>
                        </div>
                        <div class="form-group">
                            <textarea name="review_text" class="form-control" placeholder="Your Review"></textarea>
                        </div>
                        <div id="error_message" class="alert alert-danger d-none"></div>
                        <button type="button" class="btn btn-primary" onclick="validateForm()">Submit Review</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
// Client-side validation function
function validateForm() {
    const rating = document.querySelector('[name="rating"]').value;
    const reviewText = document.querySelector('[name="review_text"]').value;
    const errorMessage = document.getElementById('error_message');

    errorMessage.classList.add('d-none');
    errorMessage.innerHTML = '';

    if (isNaN(rating) || rating < 1 || rating > 5) {
        errorMessage.innerHTML = 'Rating must be between 1 and 5.';
        errorMessage.classList.remove('d-none');
        return false;
    }

    if (reviewText.split(' ').length < 3) {
        errorMessage.innerHTML = 'Review must have at least 3 words.';
        errorMessage.classList.remove('d-none');
        return false;
    }

    document.getElementById('reviewForm').submit();
}
</script>
</body>
</html>
