<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE"); // Adjust the allowed methods
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); // Add the necessary headers


include 'connection.php';


if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
    // Handle the uploaded file
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $imageName = $_FILES["image"]["name"];
        $imageSize = $_FILES["image"]["size"];
        $imageType = $_FILES["image"]["type"];
        $imageData = file_get_contents($_FILES["image"]["tmp_name"]);
    

        $stmt = $conn->prepare("INSERT INTO images (image_name, image_size, image_type, image_data) VALUES ($imageName, $imageSize, $imageType, $imageData)");
    
        if ($stmt->execute()) {
            echo "Image uploaded and data stored successfully!";
        } else {
            echo "Error uploading image and storing data: " . $stmt->error;
        }
    
    } else {
        echo "Error moving file.";
    }
} else {
    echo "Error uploading the file.";
}


?>