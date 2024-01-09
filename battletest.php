
<?php
//select pokemon and set cookies for id numbers

$pokemon1 = rand(1,721);  //TODO: GET POKEMON ID FROM USER_DEX
$pokemon2 = rand(1,721);  // GENERATE RANDOM OPPONENT


setcookie('pokemon1', $pokemon1);
setcookie('pokemon2', $pokemon2);
?>





<?php
// CONNECTS TO MYSQL AND INITIALIZES POKEMON_DATA DATABASE IF IT DOES NOT ALREADY EXIST
$host = "localhost";
$user = "root";
$pass = "";
$connection = mysqli_connect($host,$user,$pass);

if(mysqli_connect_errno()){
    die( mysqli_connect_error() );
}

//database name
$database = 'POKEMON_DATA';


//create the pokemon_data database if it does not already exist
$sql = "SHOW DATABASES LIKE '$database'";

if ( mysqli_fetch_assoc( mysqli_query($connection,$sql) ) == NULL){


    $sql = "CREATE DATABASE $database";

    $result = mysqli_query($connection,$sql);

}
else{
}

//switch to using the pokemon_data database
mysqli_query($connection,"USE POKEMON_DATA");


//checks if pokemon table already exists so it does not try to create it twice
if ( mysqli_num_rows(mysqli_query($connection,"SHOW TABLES LIKE 'pokemon'")) == 0 ){

    
    $sql = "CREATE TABLE IF NOT EXISTS POKEMON(

        ID INT PRIMARY KEY AUTO_INCREMENT,
        NAME VARCHAR(20) NOT NULL,
        TYPE1 VARCHAR(20) NOT NULL,
        TYPE2 VARCHAR(20),
        TOTAL INT NOT NULL,
        HP INT NOT NULL,
        ATK INT NOT NULL,
        DEF INT NOT NULL,
        SP_ATK INT NOT NULL,
        SP_DEF INT NOT NULL,
        SPEED INT NOT NULL,
        GENERATION INT NOT NULL,
        LEGENDARY BOOLEAN NOT NULL

    )";

    $result = mysqli_query($connection,$sql);

    $comma = ',';

    $file = fopen('pokemon_data.csv', 'r');

    while ( !feof($file) ){

        $line = fgets($file, 2040);

        $line_data = str_getcsv($line, $comma);

        $id = $line_data[0];
        $name = $line_data[1];
        $type1 = $line_data[2];

        if (isset($line_data[3])){
            $type2 = $line_data[3];
        }
        else{
            $type2 = NULL;
        }

        $total = $line_data[4];
        $hp = $line_data[5];
        $atk = $line_data[6];
        $def = $line_data[7];
        $sp_atk = $line_data[8];
        $sp_def = $line_data[9];
        $speed = $line_data[10];
        $generation = $line_data[11];
        $legendary = $line_data[12];

        //stops inserting after final pokemon to avoid inserting empty row
        if ($id < 722){

            $sql = "INSERT INTO POKEMON (ID,NAME,TYPE1,TYPE2,TOTAL,HP,ATK,DEF,SP_ATK,SP_DEF,SPEED,GENERATION,LEGEnDARY)
            VALUES ($id,'$name','$type1','$type2',$total,$hp,$atk,$def,$sp_atk,$sp_def,$speed,$generation,$legendary)";

            $result = mysqli_query($connection,$sql);

        }
        

    }

    fclose($file);

    
}


//checks if pokemon_moves table already exists so it does not try to create it twice
if ( mysqli_num_rows(mysqli_query($connection,"SHOW TABLES LIKE 'pokemon_moves'")) == 0 ){

    
    $sql = "CREATE TABLE IF NOT EXISTS POKEMON_MOVES(

        ID INT PRIMARY KEY AUTO_INCREMENT,
        NAME VARCHAR(20) NOT NULL,
        TYPE VARCHAR(20) NOT NULL,
        DAMAGE_TYPE VARCHAR(20) NOT NULL,
        PP INT NOT NULL,
        POWER INT NOT NULL,
        ACCURACY INT NOT NULL

    )";

    $result = mysqli_query($connection,$sql);

    $comma = ',';

    $file = fopen('pokemon_moves_data.csv', 'r');

    while ( !feof($file) ){

        $line = fgets($file, 2056);

        $line_data = str_getcsv($line, $comma);

        $move_name = $line_data[0];
        $move_type = $line_data[1];
        $damage_type = $line_data[2];
        $move_pp = $line_data[3];
        $move_power = $line_data[4];
        $move_accuracy = $line_data[5];

        
        $sql = "INSERT INTO POKEMON_MOVES (NAME,TYPE, DAMAGE_TYPE, PP, POWER, ACCURACY)
        VALUES ('$move_name','$move_type','$damage_type', $move_pp, $move_power, $move_accuracy)";

        $result = mysqli_query($connection,$sql);

        
        
    }

    fclose($file);

    
}


?>

<!-- USES COOKIES TO GET INITIAL DATA FOR POKEMON AND SEND IT TO BATTLE JAVASCRIPT CANVAS-->
<?php

$pokemon1_name = "";
$pokemon1_lvl = 50;      //TODO: GET LVL FROM USER_DEX
$pokemon1_hp = 0;
$pokemon1_type1 = "";
$pokemon1_type2 = "";
$pokemon1_atk = 0;
$pokemon1_def = 0;
$pokemon1_spatk = 0;
$pokemon1_spdef = 0;
$pokemon1_speed = 0;



$pokemon2_name = "";
$pokemon2_lvl = rand(($pokemon1_lvl-5),($pokemon1_lvl+5));         // GENERATE RANDOM LVL SIMILAR TO USER POKEMON LVL FOR OPPONENT
$pokemon2_hp = 0;
$pokemon2_type1 = "";
$pokemon2_type2 = "";
$pokemon2_atk = 0;
$pokemon2_def = 0;
$pokemon2_spatk = 0;
$pokemon2_spdef = 0;
$pokemon2_speed = 0;


$sql = "SELECT * FROM POKEMON WHERE ID=$pokemon1";

$result = mysqli_query($connection,$sql);

while($info = $result->fetch_assoc()) {

    $pokemon1_name = $info["NAME"];
    $pokemon1_hp = $info["HP"];
    $pokemon1_type1 = $info["TYPE1"];
    $pokemon1_type2 = $info["TYPE2"];
    $pokemon1_atk = $info["ATK"];
    $pokemon1_def = $info["DEF"];
    $pokemon1_spatk = $info["SP_ATK"];
    $pokemon1_spdef = $info["SP_DEF"];
    $pokemon1_speed = $info["SPEED"];

}
    

$sql = "SELECT * FROM POKEMON WHERE ID=$pokemon2";

$result = mysqli_query($connection,$sql);

while($info = $result->fetch_assoc()) {

    $pokemon2_name = $info["NAME"];
    $pokemon2_hp = $info["HP"];
    $pokemon2_type1 = $info["TYPE1"];
    $pokemon2_type2 = $info["TYPE2"];
    $pokemon2_atk = $info["ATK"];
    $pokemon2_def = $info["DEF"];
    $pokemon2_spatk = $info["SP_ATK"];
    $pokemon2_spdef = $info["SP_DEF"];
    $pokemon2_speed = $info["SPEED"];

}


// GETS 4 RANDOM MOVES FOR EACH POKEMON [USER AND OPPONENT]

// USER [POKEMON1]
$pokemon1_move_names = array();
$pokemon1_move_types = array();
$pokemon1_move_damages = array();
$pokemon1_move_pps = array();
$pokemon1_move_powers = array();
$pokemon1_move_accuracies = array();

$index = 0;

while ($index < 4){

    if ($pokemon1_type2 == ''){
        $sql = "SELECT * FROM POKEMON_MOVES WHERE TYPE='NORMAL' OR TYPE='$pokemon1_type1' ORDER BY RAND() LIMIT 1";
    }
    else{
        $sql = "SELECT * FROM POKEMON_MOVES WHERE TYPE='$pokemon1_type1' OR TYPE='$pokemon1_type2' ORDER BY RAND() LIMIT 1";
    }

    $result = mysqli_query($connection,$sql);

    while($moveinfo = $result->fetch_assoc()) {

        array_push($pokemon1_move_names,$moveinfo["NAME"]);
        array_push($pokemon1_move_types,$moveinfo["TYPE"]);
        array_push($pokemon1_move_damages,$moveinfo["DAMAGE_TYPE"]);
        array_push($pokemon1_move_pps,$moveinfo["PP"]);
        array_push($pokemon1_move_powers,$moveinfo["POWER"]);
        array_push($pokemon1_move_accuracies ,$moveinfo["ACCURACY"]);

    }

    $index = $index + 1;

}


// GETS COLORS FOR MOVE SELECTION BUTTONS

$move_button_colors = array();

$index = 0;

while ($index < 4){

    switch(strtoupper($pokemon1_move_types[$index])){

        case 'NORMAL':
            $move_button_colors[$index] = 'Tan';
            break;
        case 'FIRE':
            $move_button_colors[$index] = 'Orange';
            break;
        case 'WATER':
            $move_button_colors[$index] = 'Blue';
            break;
        case 'GRASS':
            $move_button_colors[$index] = 'Green';
            break;
        case 'ELECTRIC':
            $move_button_colors[$index] = 'Gold';
            break;
        case 'ICE':
            $move_button_colors[$index] = 'LightBlue';
            break;
        case 'FIGHTING':
            $move_button_colors[$index] = 'Maroon';
            break;
        case 'POISON':
            $move_button_colors[$index] = 'Purple';
            break;
        case 'FLYING':
            $move_button_colors[$index] = 'Plum';
            break;
        case 'PSYCHIC':
            $move_button_colors[$index] = 'HotPink';
            break;
        case 'BUG':
            $move_button_colors[$index] = 'Olive';
            break;
        case 'ROCK':
            $move_button_colors[$index] = 'DarkGoldenrod';
            break;
        case 'STEEL':
            $move_button_colors[$index] = 'LightGray';
            break;
        case 'DRAGON':
            $move_button_colors[$index] = 'Maroon';
            break;
        case 'DARK':
            $move_button_colors[$index] = 'Purple';
            break;
        case 'GHOST':
            $move_button_colors[$index] = 'Indigo';
            break;
        case 'FAIRY':
            $move_button_colors[$index] = 'Pink';
            break;
        case 'GROUND':
            $move_button_colors[$index] = 'Peru';
            break;


    }

    $index = $index + 1;
}

//OPPONENT [POKEMON2]

$pokemon2_move_names = array();
$pokemon2_move_types = array();
$pokemon2_move_damages = array();
$pokemon2_move_pps = array();
$pokemon2_move_powers = array();
$pokemon2_move_accuracies = array();

$index = 0;

while ($index < 4){

    if ($pokemon2_type2 == ''){
        $sql = "SELECT * FROM POKEMON_MOVES WHERE TYPE='NORMAL' OR TYPE='$pokemon2_type1' ORDER BY RAND() LIMIT 1";
    }
    else{
        $sql = "SELECT * FROM POKEMON_MOVES WHERE TYPE='$pokemon2_type1' OR TYPE='$pokemon2_type2' ORDER BY RAND() LIMIT 1";
    }

    $result = mysqli_query($connection,$sql);

    while($moveinfo = $result->fetch_assoc()) {

        array_push($pokemon2_move_names,$moveinfo["NAME"]);
        array_push($pokemon2_move_types,$moveinfo["TYPE"]);
        array_push($pokemon2_move_damages,$moveinfo["DAMAGE_TYPE"]);
        array_push($pokemon2_move_pps,$moveinfo["PP"]);
        array_push($pokemon2_move_powers,$moveinfo["POWER"]);
        array_push($pokemon2_move_accuracies ,$moveinfo["ACCURACY"]);

    }

    $index = $index + 1;

}




if ($result != NULL){
        mysqli_free_result($result);
}
    
mysqli_close($connection);



setcookie('pokemon2_move1',$pokemon2_move_names[0]);
setcookie('pokemon2_move2',$pokemon2_move_names[1]);
setcookie('pokemon2_move3',$pokemon2_move_names[2]);
setcookie('pokemon2_move4',$pokemon2_move_names[3]);

setcookie('pokemon2_move1_type', $pokemon2_move_types[0]);
setcookie('pokemon2_move2_type', $pokemon2_move_types[1]);
setcookie('pokemon2_move3_type', $pokemon2_move_types[2]);
setcookie('pokemon2_move4_type', $pokemon2_move_types[3]);

setcookie('pokemon2_move1_damage', $pokemon2_move_damages[0]);
setcookie('pokemon2_move2_damage', $pokemon2_move_damages[1]);
setcookie('pokemon2_move3_damage', $pokemon2_move_damages[2]);
setcookie('pokemon2_move4_damage', $pokemon2_move_damages[3]);

setcookie('pokemon2_move1_power', $pokemon2_move_powers[0]);
setcookie('pokemon2_move2_power', $pokemon2_move_powers[1]);
setcookie('pokemon2_move3_power', $pokemon2_move_powers[2]);
setcookie('pokemon2_move4_power', $pokemon2_move_powers[3]);

setcookie('pokemon2_move1_accuracy', $pokemon2_move_accuracies[0]);
setcookie('pokemon2_move2_accuracy', $pokemon2_move_accuracies[1]);
setcookie('pokemon2_move3_accuracy', $pokemon2_move_accuracies[2]);
setcookie('pokemon2_move4_accuracy', $pokemon2_move_accuracies[3]);


setcookie('pokemon1_name', $pokemon1_name);
setcookie('pokemon1_lvl', $pokemon1_lvl);
setcookie('pokemon1_hp', $pokemon1_hp);
setcookie('pokemon1_type1', $pokemon1_type1);
setcookie('pokemon1_type2', $pokemon1_type2);
setcookie('pokemon1_atk', $pokemon1_atk);
setcookie('pokemon1_def', $pokemon1_def);
setcookie('pokemon1_spatk', $pokemon1_spatk);
setcookie('pokemon1_spdef', $pokemon1_spdef);
setcookie('pokemon1_speed', $pokemon1_speed);



setcookie('pokemon2_name', $pokemon2_name);
setcookie('pokemon2_lvl', $pokemon2_lvl);
setcookie('pokemon2_hp', $pokemon2_hp);
setcookie('pokemon2_type1', $pokemon2_type1);
setcookie('pokemon2_type2', $pokemon2_type2);
setcookie('pokemon2_atk', $pokemon2_atk);
setcookie('pokemon2_def', $pokemon2_def);
setcookie('pokemon2_spatk', $pokemon2_spatk);
setcookie('pokemon2_spdef', $pokemon2_spdef);
setcookie('pokemon2_speed', $pokemon2_speed);


?>

<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>
<body style="Background-color:LightGray">


<canvas id="battlecanvas" width="800" height="600" style="border:2px solid black; Background-image:battlefield.png; position:relative"></canvas>


<!-- RUNS BATTLE SCRIPT AFTER DATA FOR POKEMON IS SENT TO PAGE -->
<script src="battlearena.js"></script>

<br>
<button onMouseOver="this.style.border= '5px solid white';" onMouseOut="this.style.border= '1px solid black';" style="background-color:<?php echo $move_button_colors[0]?>;border: 1px solid black;color: White;padding: 15px 32px;text-align: center;font-size: 24px;width: 300px;display: block;" onclick="turn('<?php echo $pokemon1_move_names[0]?>','<?php echo $pokemon1_move_types[0]?>','<?php echo $pokemon1_move_damages[0]?>',<?php echo $pokemon1_move_powers[0]?>,<?php echo $pokemon1_move_accuracies[0]?>)"><?php echo $pokemon1_move_names[0]?></button>
<button onMouseOver="this.style.border= '5px solid white';" onMouseOut="this.style.border= '1px solid black';" style="background-color:<?php echo $move_button_colors[1]?>;border: 1px solid black;color: White;padding: 15px 32px;text-align: center;font-size: 24px;width: 300px;display: block;" onclick="turn('<?php echo $pokemon1_move_names[1]?>','<?php echo $pokemon1_move_types[1]?>','<?php echo $pokemon1_move_damages[1]?>',<?php echo $pokemon1_move_powers[1]?>,<?php echo $pokemon1_move_accuracies[1]?>)"><?php echo $pokemon1_move_names[1]?></button>
<button onMouseOver="this.style.border= '5px solid white';" onMouseOut="this.style.border= '1px solid black';" style="background-color:<?php echo $move_button_colors[2]?>;border: 1px solid black;color: White;padding: 15px 32px;text-align: center;font-size: 24px;width: 300px;display: block;" onclick="turn('<?php echo $pokemon1_move_names[2]?>','<?php echo $pokemon1_move_types[2]?>','<?php echo $pokemon1_move_damages[2]?>',<?php echo $pokemon1_move_powers[2]?>,<?php echo $pokemon1_move_accuracies[2]?>)"><?php echo $pokemon1_move_names[2]?></button>
<button onMouseOver="this.style.border= '5px solid white';" onMouseOut="this.style.border= '1px solid black';" style="background-color:<?php echo $move_button_colors[3]?>;border: 1px solid black;color: White;padding: 15px 32px;text-align: center;font-size: 24px;width: 300px;display: block;" onclick="turn('<?php echo $pokemon1_move_names[3]?>','<?php echo $pokemon1_move_types[3]?>','<?php echo $pokemon1_move_damages[3]?>',<?php echo $pokemon1_move_powers[3]?>,<?php echo $pokemon1_move_accuracies[3]?>)"><?php echo $pokemon1_move_names[3]?></button>



<h1 style="position: absolute; top: 10px; right: 50px;">Turn summary:</h1>
<h3 style="position: absolute; top: 50px; right: 50px;" id="turn_stats"></h3>
<br>

</body>
</html>