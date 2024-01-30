<?php
$servername = "localhost";
$username = "u402017191_printPrice";
$password = "Codelock@99";
$dbname = "u402017191_printonprice";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{
    echo "Connection Success";
}
// Perform database operations here...

// Close the connection
$conn->close();
?>
