<!DOCTYPE html>
<html>

<head>
  <title>Show/Hide Div on Click</title>
  <style>
    body {
      display: flex;
      background-image: url(images/wallpaper/evolutioncenter.jpg);
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .first-div {
      position: relative;
      background-color: rgba(0, 0, 128, 0.98);
      color: white;
      width: 25%;
      border-color: white;
      border-radius: 20px;
      padding: 20px;
      margin: 20px;
      opacity: 80%;
    }

    .first-div span {
      cursor: pointer;
      text-decoration: none;
    }


    .list-of-pokemon {
      position: relative;
      background-color: rgba(0, 0, 128, 0.98);
      color: white;
      /*width: 30%;*/
      width: 100%;
      border-color: white;
      border-radius: 20px;
      padding: 20px;
      margin: 20px;
      opacity: 80%;
    }

    #list-of-pokemon {
      max-height: 400px;
      overflow: auto;
    }

    .list-of-pokemon p {
      cursor: pointer;
    }


    .to-center {
      text-align: center;
    }

    .current-pokemon {
      position: relative;
      border: 2px solid white;
      border-radius: 10px;
      opacity: 1;
      z-index: 2;
    }

    .current-pokemon img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
      opacity: 1;
      transition: opacity 0.3s ease;
      border-radius: 10px;
    }

    .current-pokemon:hover img {
      animation: pulseOpacity 1s infinite;
    }


    .current-pokemon img.animate-evolution {
      animation: pulseYellow 2.5s linear 1;
    }

    @keyframes pulseYellow {
      0% {
        transform: scale(1);
        box-shadow: 0 0 10px rgba(255, 255, 0, 0.5), 0 0 20px rgba(255, 255, 0, 0.7);
      }

      50% {
        transform: scale(1.1);
        box-shadow: 0 0 20px rgba(255, 255, 0, 0.7), 0 0 40px rgba(255, 255, 0, 0.9);
      }

      100% {
        transform: scale(1);
        box-shadow: 0 0 10px rgba(255, 255, 0, 0.5), 0 0 20px rgba(255, 255, 0, 0.7);
      }
    }


    @keyframes pulseOpacity {
      0% {
        opacity: 1;
      }

      50% {
        opacity: 0.5;
      }

      100% {
        opacity: 1;
      }
    }

    @keyframes glow {
      0% {
        transform: scale(1);
        box-shadow: 0 0 10px rgba(0, 255, 255, 0.5), 0 0 20px rgba(0, 255, 255, 0.7);
      }

      50% {
        transform: scale(1.2);
        box-shadow: 0 0 20px rgba(0, 255, 255, 0.7), 0 0 40px rgba(0, 255, 255, 0.9);
      }

      100% {
        transform: scale(1);
        box-shadow: 0 0 10px rgba(0, 255, 255, 0.5), 0 0 20px rgba(0, 255, 255, 0.7);
      }
    }
  </style>

  <script>
    // Keep track of the current selected Pokemon
    var currentPokemonName = "";

    function changePokemon(pokemonName, imageUrl, pokemonLevel, pokemonWins) {
      console.log("Selected Pokemon: " + pokemonName); // Add this line to check if the function is being called
      currentPokemonName = pokemonName;
      document.getElementById("current-pokemon").textContent = "Current Pokémon's name is: " + pokemonName;
      document.getElementById("displayed-pokemon").src = imageUrl;

      //reset glow
      document.getElementById("displayed-pokemon").classList.remove("animate-evolution");

      //remove text inside divs
      document.getElementById("pokemon-hp").textContent = "";
      document.getElementById("pokemon-atk").textContent = "";
      document.getElementById("pokemon-spatk").textContent = "";
      document.getElementById("pokemon-def").textContent = "";
      document.getElementById("pokemon-spdef").textContent = "";
      document.getElementById("pokemon-speed").textContent = "";

      //if (pokemonLevel >= 5) {
      if (pokemonWins >= 5) {
        document.getElementById("evolve-pokemon").textContent = "EVOLVE " + pokemonName.toUpperCase() + " NOW!";
        document.getElementById("evolve-pokemon").style.display = "block";
        document.getElementById("evolve-pokemon").style.cursor = "pointer";
        document.getElementById("evolve-pokemon").onclick = function() {
          evolvePokemon(pokemonName);
        };
      } else {
        document.getElementById("evolve-pokemon").style.display = "none";
      }



    }

    function fetchStatsAndUpdateUI() {
      // AJAX call to get_stats.php
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            var stats = JSON.parse(xhr.responseText);
            updateStatsUI(stats);
          } else {
            console.error("Error fetching stats");
          }
        }
      };
      xhr.open("GET", "get_stats.php?pokemonName=" + encodeURIComponent(currentPokemonName), true);
      xhr.send();
    }

    function updateStatsUI(stats) {
      // Update the stat display elements with the new stats
      //document.getElementById("pokemon-level").textContent = "Pokemon level: " + stats.pokemon_level;
      document.getElementById("pokemon-hp").textContent = "Upgraded Pokemon Health stat: " + stats.pokemon_hp;
      document.getElementById("pokemon-atk").textContent = "Upgraded Pokemon Attack stat: " + stats.pokemon_atk;
      document.getElementById("pokemon-spatk").textContent = "Upgraded Pokemon Special Attack stat: " + stats.pokemon_spatk;
      document.getElementById("pokemon-def").textContent = "Upgraded Pokemon Defense stat: " + stats.pokemon_def;
      document.getElementById("pokemon-spdef").textContent = "Upgraded Pokemon Special Defense stat: " + stats.pokemon_spdef;
      document.getElementById("pokemon-speed").textContent = "Upgraded Pokemon Speed stat: " + stats.pokemon_speed;
    }

    function evolvePokemon() {
      var xhr = new XMLHttpRequest();

      xhr.open("POST", "evolve.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
          var response = xhr.responseText;
          if (response === "success") {
            // Evolve successful, fetch updated stats and update UI
            fetchStatsAndUpdateUI();
            // alert("Pokemon evolved successfully!");



            // Add the class to the current-pokemon div
            document.getElementById("displayed-pokemon").classList.add("animate-evolution");

          } else {
            alert("Error evolving Pokemon.");
          }
        }
      };

      xhr.send("pokemonName=" + encodeURIComponent(currentPokemonName));
    }
  </script>
</head>

<body>
  <div class="first-div">
    <h1>Welcome to the Evolution Center!</h1>
    <p>
      This is where you can level up all of your owned pokemon. All Pokemon
      can be evolved up to three times, granting them upgraded strength and
      Defense! Your Pokemon will be eligible for evolution once
      it has leveled up through enough wins in battle. Evolved pokemon have
      their wins reset so they can evolve again. Take a look below and see if
      your selected pokemon can be Evolved!
    </p>
    <!-- <br /> -->

    <!--below p tag should only display the name of the current selected pokemon-->
    <p id="current-pokemon">selected pokemons name is:</p>

    <!--display image, maybe add a stock image-->

    <div class="current-pokemon">
      <img id="displayed-pokemon" alt="None Selected" />
    </div>

    <p id="evolve-pokemon" class="to-center" style="display: none;">
      <button style="cursor: pointer;">Click Me</button>
    </p>

    <!-- Stat display elements -->
    <p id="pokemon-level"></p>
    <p id="pokemon-hp"></p>
    <p id="pokemon-atk"></p>
    <p id="pokemon-spatk"></p>
    <p id="pokemon-def"></p>
    <p id="pokemon-spdef"></p>
    <p id="pokemon-speed"></p>


  </div>

  <div id="div2">
    <div id="list-of-pokemon" class="list-of-pokemon">
      <h1>Owned Pokemon</h1>
      <?php
      // Connect to the database
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "pokemon";

      $conn = new mysqli($servername, $username, $password, $dbname);

      // Check the connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      // Fetch data from the User_dex_table
      $sql = "SELECT * FROM user_dex_table";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        // Fetch data from the user_dex_table into an array
        $userDexData = array();
        while ($row = $result->fetch_assoc()) {
          $userDexData[] = $row;
        }

        // Loop through the userDexData array to process each row
        foreach ($userDexData as $userData) {
          $pokemonName = $userData["pokemon_name"];
          $pokemonLevel = $userData["pokemon_level"];
          $pokemonAtk = $userData["pokemon_hp"];
          $pokemonDef = $userData["pokemon_def"];

          //*******************UNCOMMENT THIS***********************
          $pokemonWins = $userData["pokemon_wins"];


          // Fetch the corresponding pokemon_id from pokedex_table based on pokemon_name
          $sql_pokedex = "SELECT pokemon_id FROM pokedex_table WHERE pokemon_name = '$pokemonName'";
          $result_pokedex = $conn->query($sql_pokedex);
          $pokemonIdData = $result_pokedex->fetch_assoc();
          $pokemonId = $pokemonIdData["pokemon_id"];


          // Get the list of files in the "pokemon_images" folder
          $folderPath = "images/pokemon_imgs/";
          $files = scandir($folderPath);

          // Search for the image filename that matches the Pokemon name
          foreach ($files as $filename) {
            // Check if the filename contains Pokemons name
            if (preg_match('/^\d{3}' . preg_quote($pokemonName, '/') . '\.png$/i', $filename)) {
              $imageUrl = $folderPath . $filename;
              //  echo "URL of the image for $pokemonName: $imageUrl<br>";
              break;
            }
          }

          // Add an onclick event to each Pokemon name to call the changePokemon function
          //\"$pokemonWins\"
          echo "<p class='pokemon-name' onclick='changePokemon(\"$pokemonName\", \"$imageUrl\", \"$pokemonLevel\", \"$pokemonWins\")'>$pokemonName</p>";
          echo "Current level: $pokemonLevel<br>";


          //**********************UNCOMMENT THIS ********************
          echo "Current Wins: $pokemonWins<br>";

          //echo " Current Wins: $pokemonAtk<br>";
          //  echo "Pokemnon Defense stat: $pokemonDef<br>";

          //if ($nextEvolution) {
          //echo "Evolution: " . $nextEvolution; //for now just print the evolutions name to show it is correctly acquired
          //}

          echo "<br>";
        }
      } else {
        echo "0 results";
      }

      // Close the connection
      $conn->close();
      ?>
    </div>
  </div>
</body>

</html>