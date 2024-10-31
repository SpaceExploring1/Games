<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Score List</title>
</head>
<body>
    <h1>Registered Users</h1>

    <?php
    session_start();
    if (!isset($_SESSION['user'])) {
        header("Location: scorelist.php");
        exit;
    }

    echo "<p>Welcome, " . htmlspecialchars($_SESSION['user']) . "!</p>";

    $host = "localhost";
    $user = "root";
    $pass = "root"; 
    $database = "game";

    try {
        $conn = new mysqli($host, $user, $pass, $database);

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        $query = "SELECT user FROM registration";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($row['user']) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No registered users found.</p>";
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        if ($conn) {
            $conn->close();
        }
    }
    ?>
</body>
</html>
