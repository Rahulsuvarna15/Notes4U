<?php
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if note is uploaded and there are no errors
    if (isset($_FILES['noteUpload']) && $_FILES['noteUpload']['error'] == 0) {
        // Get note details
        $noteTmpPath = $_FILES['noteUpload']['tmp_name'];
        $noteName = $_FILES['noteUpload']['name'];
        $noteSize = $_FILES['noteUpload']['size'];
        $noteType = $_FILES['noteUpload']['type'];

        // Sanitize title input (for database insertion)
        $title = htmlspecialchars(trim($_POST['title'])); 

        // Sanitize and validate the program selection
        $program = $_POST['program'];
        if (!in_array($program, ['MBA', 'MCA'])) {
            echo '<script>alert("Error: Invalid program selection."); window.location.href="upload.html";</script>';
            exit;
        }

        // Define allowed note types and max note size
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];  // Example MIME types
        $maxnoteSize = 10 * 1024 * 1024;  // 10MB

        // Check note type
        if (!in_array($noteType, $allowedTypes)) {
            echo '<script>alert("Error: Invalid note type. Only PDF, JPEG, and PNG notes are allowed."); window.location.href="upload.html";</script>';
            exit;
        }

        // Check note size
        if ($noteSize > $maxnoteSize) {
            echo '<script>alert("Error: note is too large. Maximum allowed size is 10MB."); window.location.href="upload.html";</script>';
            exit;
        }

        // Read the contents of the uploaded file
        $noteDataContent = file_get_contents($noteTmpPath);

        // Database connection details
        $host = 'localhost';
        $dbname = 'test1'; // Your database name
        $username = 'root'; // Your MySQL username
        $password = ''; // Your MySQL password
        $port = 3307;  // MySQL server port (change it if needed)

        try {
            // Create a PDO connection to the database
            $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare SQL to insert note data into the database
            $sql = "INSERT INTO note(note_name, note_size, note_type, note_data, title, program) 
                    VALUES (:note_name, :note_size, :note_type, :note_data, :title, :program)";
            $stmt = $pdo->prepare($sql);

            // Bind parameters and execute the query
            $stmt->bindParam(':note_name', $noteName);
            $stmt->bindParam(':note_size', $noteSize);
            $stmt->bindParam(':note_type', $noteType);
            $stmt->bindParam(':note_data', $noteDataContent, PDO::PARAM_LOB); // Store binary data
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':program', $program);
            $stmt->execute();

            // Show success message and redirect
            echo '<script>
                alert("Note uploaded successfully!");
                window.location.href = "pro.html"; 
              </script>';
            exit();

        } catch (PDOException $e) {
            // Handle database connection or query errors
            echo '<script>alert("Error: Unable to save note data to the database. Please try again later."); window.location.href="pro.html";</script>';
        }
    } else {
        // Handle note upload errors
        echo '<script>alert("Error: No note uploaded or upload error."); window.location.href="pro.html";</script>';
    }
} else {
    echo "Invalid request method.";
}
?>
