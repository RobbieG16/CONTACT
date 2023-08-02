<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "id21046178_webprac";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted and the file was uploaded
if (isset($_POST["submit"])) {
    $file_name = $_FILES["file"]["name"];
    $file_path = $_FILES["file"]["tmp_name"];

    // Prepare the SQL statement to insert the file details into the database
    $stmt = $conn->prepare("INSERT INTO pdf_files (file_name, file_path) VALUES (?, ?)");
    $stmt->bind_param("ss", $file_name, $file_path);

    // Execute the statement
    if ($stmt->execute()) {
       
    // Close the statement
    $stmt->close();

    // Redirect back to the main PHP file after successful upload
    header("Location: main.php");
    exit();
    }else {
        echo "Error uploading file: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
