<?php
// Database connection details
$host = 'localhost';
$dbname = 'test1'; // Your database name
$username = 'root'; // Your MySQL username
$password = ''; // Your MySQL password
$port = 3307; // MySQL server port (change it if needed)

if (isset($_GET['id'])) {
    $noteId = $_GET['id'];

    try {
        // Create a PDO connection to the database
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch the specific note details from the database
        $sql = "SELECT id, title, note_type, note_data FROM note WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $noteId, PDO::PARAM_INT);
        $stmt->execute();

        $note = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($note) {
            // Display the title
            echo "<h2>Title: " . htmlspecialchars($note['title']) . "</h2>";

            // Display the note content (binary data)
            // Set headers to handle the file content correctly
            header("Content-Type: " . $note['note_type']);
            header("Content-Disposition: inline; filename=" . $note['title']);
            echo $note['note_data'];  // Output the actual note content (the file itself)
            exit;
        } else {
            echo "<h2>Note not found.</h2>";
        }
    } catch (PDOException $e) {
        echo "<h2>Error: " . $e->getMessage() . "</h2>";
        exit;
    }
} else {
    echo "<h2>Invalid request.</h2>";
    exit;
}
