<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Assuming you have a database connection established
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

// Function to sanitize the file name
function sanitizeFileName($filename) {
    $filename = preg_replace("/[^A-Za-z0-9_.\-]/", '_', $filename); // Replace non-alphanumeric characters with underscore
    $filename = preg_replace("/_{2,}/", "_", $filename); // Remove consecutive underscores
    return $filename;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['fileToUpload'])) {
        $file = $_FILES['fileToUpload'];
        $file_name = sanitizeFileName($file['name']);
        $file_tmp = $file['tmp_name'];

        // Check if file is a valid image, pdf, or word file
        $allowedExtensions = array("jpg", "jpeg", "png", "gif", "pdf", "doc", "docx");
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowedExtensions)) {
            echo "Sorry, only JPG, JPEG, PNG, GIF, PDF, DOC, and DOCX files are allowed.";
            exit();
        }

        // Check file size (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            echo "Sorry, your file is too large. Max file size is 5MB.";
            exit();
        }

        // Move the uploaded file to the uploads directory
        $uploadsDir = "uploads/";
        $file_path = $uploadsDir . $file_name;
        if (!move_uploaded_file($file_tmp, $file_path)) {
            echo "Sorry, there was an error uploading your file.";
            exit();
        }

        // Insert file data into the database with "Processed" status as false (0)
        $sql = "INSERT INTO pdf_files (file_name, file_path, Processed) VALUES ('$file_name', '$file_path', 0)";
        if ($conn->query($sql) === TRUE) {
            echo "File uploaded successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "No file uploaded.";
    }
}

// Close the connection
$conn->close();
?>
