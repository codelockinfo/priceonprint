<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); 

include 'connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (isset($_FILES['image']['name'])) {

    $target_dir = "uploads/";
    $timestamp = time();
    $target_file = $target_dir . basename($timestamp . '_' .$_FILES["image"]["name"]);

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $imageName = $timestamp . '_' .$_FILES["image"]["name"];
        $imageSize = $_FILES["image"]["size"];
        $imageType = $_FILES["image"]["type"];

        $file_data = isset($_POST['height']) ? $_POST['height'].'_'.$_POST['width'] : '';
        
        $stmt = $conn->prepare("INSERT INTO images (image_name, image_size, image_type, image_data) VALUES ('$imageName', '$imageSize', '$imageType','$file_data')");
        $domain = $_SERVER['HTTP_HOST'];

        if ($stmt->execute()) {
            $result = array(
                "result" => "success",
                "msg" => "Image uploaded and data stored successfully!",
                "data" => array("domain" => $domain,"file_name" => $_FILES["image"]["tmp_name"])
            );
            
        } else {
            $result = array(
                "result" => "fail",
                "msg" => "Something went wrong"
            );
        }
        echo json_encode($result);
    } else {
        echo "Error moving file.";
    }
} else {
    echo "Error uploading the file.";
}


?>