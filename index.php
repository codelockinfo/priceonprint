<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE"); // Adjust the allowed methods
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); // Add the necessary headers
echo "<pre>";
print_r($_POST);
echo "</pre>";


?>