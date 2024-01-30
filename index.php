<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE"); // Adjust the allowed methods
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); // Add the necessary headers

$servername = "localhost";
$username = "u402017191_printPrice";
$password = "Codelock@99";
$dbname = "u402017191_printonprice";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

print_r($_FILES);
if (isset($_FILES['image']['name'])) {
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