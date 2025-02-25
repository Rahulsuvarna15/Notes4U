<?php
session_start(); // Start the session to manage login state

// Database connection details
$host = 'localhost';
$dbname = 'test1';
$username = 'root';
$password = '';
$port = 3307;

try {
    // Create a PDO connection to the database
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Input validation
    if (empty($email) || empty($password)) {
        echo '<script>
                alert("Please enter both email and password.");
                window.location.href = "login.php";
              </script>';
        exit();
    }

    try {
        // Check if the user exists with the provided email
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Password is correct, start a session and store user info
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['usn'] = $user['usn'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['program'] = $user['program'];

            // Show success alert and redirect to upload page
            echo '<script>
                    alert("Login Successful");
                    window.location.href = "pro.html";
                  </script>';
            exit();
        } else {
            // Invalid login credentials
            echo '<script>
                    alert("Invalid email or password.");
                    window.location.href = "log.html";
                  </script>';
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
