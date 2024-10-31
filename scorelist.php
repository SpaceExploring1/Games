<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Score List</title>
</head>
<body>
    <h1>Scorelijst van Geregistreerde Gebruikers</h1>

    <?php
    session_start(); // Start de sessie om toegang te krijgen tot sessievariabelen
    
    // Controleer of de gebruiker is ingelogd; zo niet, doorverwijzen naar inlogpagina
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }

    // Welkomstbericht voor de ingelogde gebruiker
    echo "<p>Welkom, " . htmlspecialchars($_SESSION['user']) . "!</p>";

    // Databaseverbinding instellen
    $host = "localhost";
    $user = "root";
    $pass = "root"; 
    $database = "game";

    try {
        // Maak verbinding met de database
        $conn = new mysqli($host, $user, $pass, $database);

        // Controleer op fouten in de verbinding
        if ($conn->connect_error) {
            throw new Exception("Verbindingsfout: " . $conn->connect_error);
        }

        // Query om gebruikers en scores op te halen uit de registratie-tabel
        $query = "SELECT user, score FROM registration ORDER BY score DESC";
        $result = $conn->query($query);

        // Controleer of er resultaten zijn gevonden
        if ($result->num_rows > 0) {
            // Start de tabel
            echo "<table>";
            echo "<tr><th>Gebruiker</th><th>Score</th></tr>"; // Tabelkoppen voor gebruiker en score

            // Resultaten doorlopen en elke rij in de tabel tonen
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['user']) . "</td>"; // Gebruikersnaam
                echo "<td>" . htmlspecialchars($row['score']) . "</td>"; // Score
                echo "</tr>";
            }

            echo "</table>"; // Sluit de tabel af
        } else {
            // Geen resultaten gevonden, een bericht tonen
            echo "<p>Geen geregistreerde gebruikers gevonden.</p>";
        }

    } catch (Exception $e) {
        // Toon foutmelding indien er een uitzondering optreedt
        echo "Fout: " . $e->getMessage();
    } finally {
        // Sluit de databaseverbinding af
        if ($conn) {
            $conn->close();
        }
    }
    ?>
</body>
</html>
