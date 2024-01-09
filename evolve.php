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

// Get the Pokemon name from the AJAX request
if (isset($_POST["pokemonName"])) {
    $pokemonName = $_POST["pokemonName"];

    // Update the user_dex_table to increase values by 25% for the given Pokemon
    $sql_update = "UPDATE user_dex_table 
                SET pokemon_level = pokemon_level + 1,
                    pokemon_hp = ROUND(pokemon_hp * 1.15),
                    pokemon_atk = ROUND(pokemon_atk * 1.15),
                    pokemon_def = ROUND(pokemon_def * 1.15),
                    pokemon_spatk = ROUND(pokemon_spatk * 1.15),
                    pokemon_spdef = ROUND(pokemon_spdef * 1.15),
                    pokemon_speed = ROUND(pokemon_speed * 1.15),
                    pokemon_wins = 0
                WHERE pokemon_name = '$pokemonName'";

    if ($conn->query($sql_update) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }

    // Close the connection
    $conn->close();
} else {
    echo "error";
}
