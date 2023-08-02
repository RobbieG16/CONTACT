<?php
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

// Fetch files to be processed from the database
$sql = "SELECT id, file_name, file_path FROM pdf_files WHERE Processed = 0"; // Assuming 0 indicates files to be processed
$result = $conn->query($sql);

$filesToProcess = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fileData = array(
            'id' => $row['id'],
            'file_name' => $row['file_name'],
            'file_path' => $row['file_path']
        );
        $filesToProcess[] = $fileData;
    }
}

// Close the connection
$conn->close();

// Return the data in JSON format
header('Content-Type: application/json');
echo json_encode($filesToProcess);
?>
