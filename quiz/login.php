<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
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
session_start(); // Start de sessie voor de gebruiker
ini_set('display_errors', 1); // Schakel foutmeldingen in voor debugging
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $host = "localhost";
    $user = "root";
    $pass = "root"; 
    $database = "game";

    try {
        // Maak verbinding met de database
        $connection = new mysqli($host, $user, $pass, $database);

        // Controleer op verbindingsfout en gooi een uitzondering
        if ($connection->connect_error) {
            throw new Exception("Connection failed: " . $connection->connect_error);
        }

        // Ophalen van formulierdata
        $username = $_POST['user'];
        $password = $_POST['password'];

        // Voorbereiden en uitvoeren van de query om gebruikersgegevens op te halen
        $query = "SELECT * FROM registration WHERE user = ?";
        $statement = $connection->prepare($query); // Gebruik de correcte variabele $connection
        $statement->bind_param("s", $username); // Alleen gebruikersnaam binden, wachtwoord niet in de query
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) { 
            // Controleer of er een gebruiker met de opgegeven gebruikersnaam is gevonden.
            $row = $result->fetch_assoc();
        
            // Verifieer het wachtwoord
            if (password_verify($password, $row['password'])) {
                // Start een sessie en stuurt de gebruiker door naar scorelist.php bij een succesvolle inlog.
                $_SESSION['user'] = $username; // Zet de sessievariabele
                echo "<p>Gebruiker ingelogd: " . $_SESSION['user'] . "</p>"; // Debugging: toon sessievariabele
                header("Location: scorelist.php");
                exit;
            } else {
                // Een foutmelding bij een onjuist wachtwoord weergeven.
                echo "<p>Onjuist wachtwoord.</p>";
            }
        } else {
            // Geeft een melding als de gebruiker niet bestaat/niet geregistreerd heeft.
            echo "<p>Gebruiker niet gevonden.</p>";
        }
        $statement->close(); // Sluit de statement
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage(); // Toon de foutmelding als er een uitzondering wordt gegooid
    } finally {
        if ($connection) {
            $connection->close(); // Sluit de databaseverbinding
        }
    }
}
?>

</body>
</html>
