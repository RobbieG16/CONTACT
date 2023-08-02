<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            font-family: Arial, sans-serif;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Styling for the button */
        .custom-button {
            display: block;
            width: 150px;
            height: 40px;
            background-color: #007BFF;
            color: #fff;
            font-family: Arial, sans-serif;
            font-size: 16px;
            text-align: center;
            line-height: 40px;
            border-radius: 20px;
            box-shadow: 0px 2px 4px #000;
            margin: 20px auto;
            cursor: pointer;
            text-decoration: none;
        }

        /* Hidden file input */
        input[type="file"] {
            display: none;
        }
    </style>
    <script>
        // Function to handle file selection and upload
        function handleFileUpload() {
            const fileInput = document.getElementById('fileToUpload');
            fileInput.click(); // Open the file picker

            fileInput.addEventListener('change', () => {
                const files = fileInput.files;
                if (files.length > 0) {
                    const formData = new FormData();
                    formData.append('fileToUpload', files[0]);

                    // Send the file to the server using AJAX
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'upload_file.php', true);
                    xhr.onload = function() {
                        // Refresh the table after successful upload
                        if (xhr.status === 200) {
                            location.reload();
                        } else {
                            console.error('Error uploading file.');
                        }
                    };
                    xhr.send(formData);
                }
            });
        }
    </script>
</head>
<body>
    <table>
        <tr>
            <th>Files</th>
            <th>Directory</th>
            <th>Processed</th>
            <th>Status</th>
        </tr>
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

        // Fetch file data from the database
        $sql = "SELECT file_name, file_path, Processed FROM pdf_files";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['file_name'] . "</td>";
                echo "<td>" . $row['file_path'] . "</td>";
                echo "<td></td>"; // Leave the "Processed" column blank for future processed files
                echo "<td>" . ($row['Processed'] ? 'Yes' : 'No') . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No files found.</td></tr>";
        }

        // Close the connection
        $conn->close();
        ?>
    </table>
    <!-- Button below the table -->
    <a href="#" class="custom-button" onclick="handleFileUpload()">Upload File</a>
    <!-- Hidden file input -->
    <input type="file" id="fileToUpload" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
</body>
</html>
