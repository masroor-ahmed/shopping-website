<?php
// Check if the image name is provided
if (isset($_POST['image_name'])) {
    // Directory where the images are stored
    $uploadDir = 'uploads/product-images/';

    // Sanitize the image name to prevent directory traversal
    $imageName = basename($_POST['image_name']);

    // Path to the image file
    $imagePath = $uploadDir . $imageName;

    // Check if the image file exists
    if (file_exists($imagePath)) {
        // Attempt to delete the image file
        if (unlink($imagePath)) {
            // Image deleted successfully
            echo 'Image deleted successfully.';
        } else {
            // Failed to delete the image file
            echo 'Failed to delete the image.';
        }
    } else {
        // Image file not found
        echo 'Image not found.';
    }
} else {
    // No image name provided
    echo 'Image name not provided.';
}
?>
