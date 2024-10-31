<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Score List</title>
</head>
<body>
    <h1>Registered Users</h1>

    <?php
    session_start(); // Start de sessie om sessievariabelen te kunnen gebruiken

    // Controleer of de gebruiker is ingelogd; zo niet, doorverwijzen naar login.php
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }

    // Welkomstbericht voor de ingelogde gebruiker
    echo "<p>Welcome, " . htmlspecialchars($_SESSION['user']) . "!</p>";

    // Databaseverbinding configureren
    $host = "localhost";
    $user = "root";
    $pass = "root"; 
    $database = "game";

    try {
        // Maak verbinding met de database
        $connection = new mysqli($host, $user, $pass, $database);

        // Controleer of er een fout is bij de verbinding
        if ($connection->connect_error) {
            throw new Exception("Connection failed: " . $connection->connect_error);
        }

        // Query om alle gebruikers uit de registratie-tabel op te halen
        $query = "SELECT user FROM registration";
        $result = $connection->query($query);

        // Controleer of er gebruikers in de tabel staan
        if ($result->num_rows > 0) {
            echo "<ul>";
            // Loop door de resultaten en toon elke gebruikersnaam in een lijst
            while ($row = $result->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($row['user']) . "</li>";
            }
            echo "</ul>";
        } else {
            // Geef een melding als er geen geregistreerde gebruikers zijn gevonden
            echo "<p>No registered users found.</p>";
        }

    } catch (Exception $e) {
        // Toon foutmelding indien een uitzondering wordt opgevangen
        echo "Error: " . $e->getMessage();
    } finally {
        // Sluit de databaseverbinding
        if ($connection) {
            $connection->close();
        }
    }
    ?>
</body>
</html>
