<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
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
        $connection = new mysqli($host, $user, $pass, $database);

        if ($connection->connect_error) {
            throw new Exception("Connection failed: " . $connection->connect_error);
        }

        // De data van het formulier geven voor de lijst
        $username = $_POST['user'];
        $password = $_POST['password'];
        $repeatPassword = $_POST['repeat-password'];

        // kijken of ze hetzelfde zijn
        if ($password !== $repeatPassword) {
            echo "<p>Passwords do not match.</p>";
            exit;
        }

        // Hash het wachtwoord
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Check if the user already exists
        $checkUserQuery = "SELECT * FROM registration WHERE user = ?";
        // Bereidt een SQL-query voor om te controleren of de opgegeven gebruikersnaam al in de tabel 'registration' bestaat.
        $satement = $connection->prepare($checkUserQuery);
        $satement->bind_param("ss", $username, $password);
        $satement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) { 
            // Controleert of er rijen terugkomen van de databasequery, wat betekent dat de gebruiker al bestaat.
            echo "<p>Gebruiker bestaat al.</p>";
            exit; // Stopt verdere uitvoering als een gebruiker met dezelfde gebruikersnaam is gevonden.
        }

        // Insert into the database
        $query = "INSERT INTO registration (user, password) VALUES (?, ?)";
        $statement = $connection->prepare($query);
        $statement->bind_param("ss", $username, $hashedPassword); //bind de gebruikersnaam en wachtwoord met hash in de database

        $statement->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        if ($connection) {
            $connection->close();
        }
    }
}
?>

</body>
</html>
