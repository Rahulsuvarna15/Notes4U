<?php
// Database connection details
$host = 'localhost';
$dbname = 'test1';
$username = 'root';
$password = '';
$port=3307;

try {
    // Create a PDO connection to the database
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect the user inputs
    $name = $_POST['name'];
    $usn = $_POST['usn']; 
    $email = $_POST['email'];
    $password = $_POST['password'];
    $program = $_POST['program'];

    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Insert the data into the database
        $sql = "INSERT INTO users (name,usn, email, password, program) VALUES (:name, :usn,:email, :password, :program)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':usn', $usn);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':program', $program);

        // Execute the query
        $stmt->execute();
        echo "Registration successful! Please <a href='log.html'>login</a> to continue.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>