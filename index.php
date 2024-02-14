<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); 

include 'connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getFullDomain() {
    // Check if SSL is enabled - consider both reverse proxy and direct scenarios
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
                || (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && $_SERVER['HTTP_FRONT_END_HTTPS'] !== 'off');

    // Define protocol based on $isSecure check
    $protocol = $isSecure ? 'https://' : 'http://';

    // Get the server name (e.g., www.example.com)
    $serverName = $_SERVER['HTTP_HOST']; // This includes the port number if different from default

    // Construct full URL
    $fullDomain = $protocol . $serverName;

    return $fullDomain;
}


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
        $full_domain = getFullDomain();

        $file_extension = pathinfo($imageName, PATHINFO_EXTENSION);
        $dimensions = getimagesize($target_file);
        if ($dimensions !== false) {
            $width = $dimensions[0]; // Width is at index 0
            $height = $dimensions[1]; // Height is at index 1
        } else {
            $width =  "Failed to get dimensions.";
            $height = "Failed to get dimensions.";
        }

        if ($stmt->execute()) {
            $result = array(
                "result" => "success",
                "type" => $file_extension,
                "width" => $width,
                "height" => $height,
                "target_file" => $target_file,
                "msg" => "Image uploaded and data stored successfully!",
                "data" => array("file_link" => $full_domain.'/priceonprint/uploads/'.$imageName)
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