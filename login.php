<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Login</title>
</head>
<body>
    <form action="quiz.php" method="POST">
        <h1>Login</h1>
        <label for="user">User:</label><br>
        <input type="text" name="user" required><br><br>
        <label for="password">Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>

    <?php
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $host = "localhost";
        $user = "root";
        $pass = "root"; 
        $database = "game";

        try {
            $conn = new mysqli($host, $user, $pass, $database);

            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }

            // Get form data
            $username = $_POST['user'];
            $password = $_POST['password'];

            // Prepare and execute the query to get user data
            $query = "SELECT * FROM registration WHERE user = ?";
            $statement = $conn->prepare($query);
            $statement->bind_param("s", $username);
            $statement->execute();
            $result = $statement->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Verify the password
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user'] = $username;
                    header("Location: scorelist.php");
                    exit;
                } else {
                    echo "<p>Incorrect password.</p>";
                }
            } else {
                echo "<p>User not found.</p>";
            }

            $statement->close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            if ($conn) {
                $conn->close();
            }
        }
    }
    ?>
</body>
</html>
