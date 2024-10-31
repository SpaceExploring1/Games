<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Registration Page</title>
</head>
<body>
    <form action="login.php" method="POST">
        <h1>Register</h1>
        <label for="user">User:</label><br>
        <input type="text" name="user" required><br><br>
        <label for="password">Password:</label><br>
        <input type="password" name="password" required><br><br>
        <label for="repeat-password">Repeat Password:</label><br>
        <input type="password" name="repeat-password" required><br><br>
        <input type="submit" value="Register">
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
        $repeatPassword = $_POST['repeat-password'];

        // Check if passwords match
        if ($password !== $repeatPassword) {
            echo "<p>Passwords do not match.</p>";
            exit;
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Check if the user already exists
        $checkUserQuery = "SELECT * FROM registration WHERE user = ?";
        $stmt = $conn->prepare($checkUserQuery);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<p>User already exists.</p>";
            exit;
        }

        // Insert into the database
        $query = "INSERT INTO registration (user, password) VALUES (?, ?)";
        $statement = $conn->prepare($query);
        $statement->bind_param("ss", $username, $hashedPassword);

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
