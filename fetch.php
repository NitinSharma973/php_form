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
    // Get the email from the form
    $email = $_POST['email'];

    // Fetch the user's health report from the database
    $stmt = $conn->prepare("SELECT r.file_name FROM reports r INNER JOIN users u ON u.id = r.user_id WHERE u.email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result) {
        $fileName = $result['file_name'];
        $filePath = "uploads/" . $fileName;

        // Check if the file exists
        if (file_exists($filePath)) {
            // Set the appropriate headers for PDF file download
            header("Content-Type: application/pdf");
            header("Content-Disposition: inline; filename=" . $fileName);

            // Output the file content
            readfile($filePath);
            exit;
        } else {
            echo "Health report not found.";
        }
    } else {
        echo "User not found.";
    }
}
?>