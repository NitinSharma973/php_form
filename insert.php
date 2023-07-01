<?php
// Replace the database credentials with your own
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "assignment";

// Create a new PDO instance
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $age = $_POST['age'];
    $weight = $_POST['weight'];
    $email = $_POST['email'];

    // Insert the user details into the database
    $stmt = $conn->prepare("INSERT INTO users (name, age, weight, email) VALUES (:name, :age, :weight, :email)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':age', $age);
    $stmt->bindParam(':weight', $weight);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Get the last inserted user ID
    $userId = $conn->lastInsertId();

    // Upload the PDF file
    $targetDir = "uploads/";
    $fileName = $_FILES['health-report']['name'];
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['health-report']['tmp_name'], $targetFilePath)) {
        // Insert the file details into the database
        $stmt = $conn->prepare("INSERT INTO reports (user_id, file_name) VALUES (:user_id, :file_name)");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':file_name', $fileName);
        $stmt->execute();

        echo "User details and file uploaded successfully.";
    } else {
        echo "Error uploading the file.";
    }
}
?>