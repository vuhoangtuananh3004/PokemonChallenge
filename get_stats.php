<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pokemon";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET["pokemonName"])) {
    $pokemonName = $_GET["pokemonName"];

    $sql = "SELECT pokemon_level, pokemon_hp, pokemon_atk, pokemon_def, pokemon_spatk, pokemon_spdef, pokemon_speed, pokemon_wins
          FROM user_dex_table
          WHERE pokemon_name = '$pokemonName'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $stats = $result->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode($stats);
    } else {
        echo "Pokemon not found";
    }
} else {
    echo "Invalid request";
}

// Close the connection
$conn->close();
