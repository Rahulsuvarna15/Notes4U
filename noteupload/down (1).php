<?php
// Database connection details
$host = 'localhost';
$dbname = 'test1'; // Your database name
$username = 'root'; // Your MySQL username
$password = ''; // Your MySQL password
$port = 3307; // MySQL server port (change it if needed)

try {
    // Create a PDO connection to the database
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch MBA notes from the database
    $sql_mba = "SELECT id, title, program, note_type FROM note WHERE program = 'MBA'";
    $stmt_mba = $pdo->prepare($sql_mba);
    $stmt_mba->execute();
    $mba_notes = $stmt_mba->fetchAll(PDO::FETCH_ASSOC);

    // Fetch MCA notes from the database
    $sql_mca = "SELECT id, title, program, note_type FROM note WHERE program = 'MCA'";
    $stmt_mca = $pdo->prepare($sql_mca);
    $stmt_mca->execute();
    $mca_notes = $stmt_mca->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Uploaded Notes</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        /* Navigation Bar Styles */
        .navbar {
            background-color: #007bff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar .logo {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
        }

        .navbar-links {
            display: flex;
            gap: 20px;
        }

        .navbar-links a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .navbar-links a:hover {
            background-color: #0056b3;
        }

        .container {
            max-width: 1200px;
            margin: 70px auto 50px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            font-size: 2rem;
            color: #333;
            margin-bottom: 30px;
        }

        .note-item {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .note-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .note-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #007bff;
            margin-bottom: 10px;
        }

        .note-details {
            font-size: 1rem;
            color: #555;
            margin-bottom: 10px;
        }

        .note-program {
            font-weight: bold;
            color: #333;
        }

        .note-type {
            font-style: italic;
            color: #6c757d;
        }

        .view-button {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
            transition: background-color 0.3s ease;
            display: inline-block;
        }

        .view-button:hover {
            background-color: #0056b3;
        }

        .no-notes {
            text-align: center;
            font-size: 1.2rem;
            color: #888;
        }

        .program-section {
            margin-bottom: 30px;
        }

        .program-title {
            background-color: #007bff;
            color: white;
            padding: 10px;
            font-size: 1.5rem;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .note-title {
                font-size: 1.3rem;
            }

            .view-button {
                padding: 10px 15px;
                font-size: 0.9rem;
            }

            .navbar-links {
                display: none;
            }

            .navbar {
                justify-content: space-between;
            }

            .navbar-menu {
                display: block;
            }
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <a href="#" class="logo">College Notes</a>
        <div class="navbar-links">
            <a href="#">Home</a>
            <a href="#mba-notes">MBA Notes</a>
            <a href="#mca-notes">MCA Notes</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h2>Uploaded College Notes</h2>

        <!-- MBA Section -->
        <div class="program-section" id="mba-notes">
            <div class="program-title">MBA Notes</div>

            <?php if (count($mba_notes) > 0): ?>
                <?php foreach ($mba_notes as $note): ?>
                    <div class="note-item">
                        <div class="note-title"><?php echo htmlspecialchars($note['title']); ?></div>
                        <div class="note-details">
                            <div class="note-program">Program: <?php echo htmlspecialchars($note['program']); ?></div>
                            <div class="note-type">Type: <?php echo htmlspecialchars($note['note_type']); ?></div>
                        </div>
                        <a href="down1.php?id=<?php echo $note['id']; ?>" class="view-button">View Note</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-notes">No MBA notes have been uploaded yet.</div>
            <?php endif; ?>
        </div>

        <!-- MCA Section -->
        <div class="program-section" id="mca-notes">
            <div class="program-title">MCA Notes</div>

            <?php if (count($mca_notes) > 0): ?>
                <?php foreach ($mca_notes as $note): ?>
                    <div class="note-item">
                        <div class="note-title"><?php echo htmlspecialchars($note['title']); ?></div>
                        <div class="note-details">
                            <div class="note-program">Program: <?php echo htmlspecialchars($note['program']); ?></div>
                            <div class="note-type">Type: <?php echo htmlspecialchars($note['note_type']); ?></div>
                        </div>
                        <a href="down1.php?id=<?php echo $note['id']; ?>" class="view-button">View Note</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-notes">No MCA notes have been uploaded yet.</div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
