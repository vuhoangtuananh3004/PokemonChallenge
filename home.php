<?php
require('database/Models/Userdex.php');
require('database/Models/User.php');
session_start();

if (!isset($_SESSION['user_data'])) {
    header('location:index.php');
}

$user_data = $_SESSION['user_data'];
$user_id = $user_data['id'];

$user = new User();
$user->setUserId($user_id);
$user_info = json_encode($user->get_user_data_by_id());

$user_dex = new Userdex($user->getUserId());
$user_dex = json_encode($user_dex->get_user_pokedex_by_id());

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/parley.js/dist/layout.css" />
    <link rel="stylesheet" href="index.css">
    <link href="https://fonts.googleapis.com/css?family=Bangers&display=swap" rel="stylesheet">
    <title>POKEMON BATTLE</title>
</head>

<body>
    <!-- <div class="flex h-screen w-screen justify-center items-center"> -->
    <div class="flex flex-col  h-screen w-screen bg-cover bg-no-repeat bg-center text-white overflow-hidden" id="home_div" style="background-image: url(images/wallpaper/home.png)">
        <!-- Level and User Name go here , HEADER--SECTION -->
        <?php include 'header.php'; ?>
        <!-- DUEGON GO HEAR -->
        <audio loop style="color: white;" src="./database/file/audio_files/hanada.wav" id="hanada"></audio>
        <audio loop style="color: white;" src="./database/file/audio_files/nivcity.wav" id="nivcity"></audio>
        <div class="absolute bottom-[300px] left-[300px] h-[400px] w-[300px] shadow-inner" id="go_to_duegon">
            <div class="flex flex-col h-full w-full justify-center items-center">
                <span class="text-xl mb-5 bg-red-900/50 px-5 rounded-[48px]">Duegon</span>
                <div class="h-[300px] w-[300px] rounded-full scale-x-50 shadow-[-10px_-5px_40px_0px_rgba(0,0,0,250)] bg-cover bg-no-repeat bg-center rounded-full cursor-pointer" style="background-image: url(images/homeImg/duegon_gate.gif);" name="setting" id="setting">
                </div>
            </div>
        </div>
        <!-- START POKEMON LAB SECTION -->
        <?php include 'pokemon_lab.php' ?>
        <!-- END POKEMON LAB SECTION -->

        <!-- START POKEMON CENTER SECTION -->
        <div class="flex hidden h-full w-full items-center justify-center z-30" id="pokemon_center_open">
            <div class="flex flex-col h-[800px] w-[800px] justify-end items-center bg-black/50 rounded-[48px] drop-shadow-2xl p-5 border-2 border-red-300">
                <div class="flex w-full justify-end p-2 text-2xl cursor-pointer" id="pokemon_center_close">X</div>
                <div class="h-full w-full" id="pokemon_center_contain"></div>
            </div>
        </div>
    </div>
    <!-- END POKEMON CENTER SECTION -->


    <script>
        $(document).ready(function() {
            var divElement = $("#home_div");
            var divHeight = divElement.height();
            var divWidth = divElement.width();
            // START IMPORT HOUSE LOCATION
            const house_map = [{
                    id: "pokemon_lab",
                    name: "Pokemon Lab",
                    h: "h-[" + Math.round(0.10 * divHeight) + "px] ",
                    w: "w-[" + Math.round(0.057 * divWidth) + "px] ",
                    t: "top-[" + Math.round(divHeight * 0.46) + "px] ",
                    l: "left-[" + Math.round(divWidth * 0.43) + "px] ",
                },
                {
                    id: "arena",
                    name: "Arena",
                    h: "h-[" + Math.round(0.18 * divHeight) + "px] ",
                    w: "w-[" + Math.round(0.1 * divWidth) + "px] ",
                    t: "top-[" + Math.round(divHeight * 0.44) + "px] ",
                    l: "left-[" + Math.round(divWidth * 0.7) + "px] ",
                },
                {
                    id: "pokemon_center",
                    name: "Pokemon Center",
                    h: "h-[" + Math.round(0.12 * divHeight) + "px] ",
                    w: "w-[" + Math.round(0.1 * divWidth) + "px] ",
                    t: "top-[" + Math.round(divHeight * 0.56) + "px] ",
                    l: "left-[" + Math.round(divWidth * 0.45) + "px] ",
                },
                {
                    id: "evolution_center",
                    name: "Evolution Center",
                    h: "h-[" + Math.round(0.1 * divHeight) + "px] ",
                    w: "w-[" + Math.round(0.075 * divWidth) + "px] ",
                    t: "top-[" + Math.round(divHeight * 0.56) + "px] ",
                    l: "left-[" + Math.round(divWidth * 0.624) + "px] ",
                }
            ];
            house_map.map((value, index) => {
                var house = $("<div> </div>");
                house.attr("id", value.id);
                var house_class = "absolute " + value.h + value.w + value.t + value.l + " z-10 cursor-pointer";
                var house_inner = $("<div></div>");
                house_inner.text(value.name);
                var house_inner_class = "flex justify-center items-center text-red-800 text-center font-bold bg-white/90 rounded-xl"
                house_inner.addClass(house_inner_class);
                house.addClass(house_class);
                house.append(house_inner);
                divElement.append(house);
            })
            // END IMPORT HOUSE LOCATION
            // ----------------------START POKEMON LAB--------------------------
            $('#pokemon_lab').click(() => {
                $('#pokemon_lab_open').removeClass('hidden');
                let pokelab_audio = document.getElementById("hanada");
                pokelab_audio.volume = .2;
                pokelab_audio.play();
            });
            $('#pokemon_lab_close').click(() => {
                $('#pokemon_lab_open').addClass('hidden');
                $('#display_draw_pokemon').empty();
                let pokelab_audio = document.getElementById("hanada");
                pokelab_audio.pause();
            })
            // END POKEMON_LAB
            // -------------------------START POKEMONCENTER------------------------
            $('#pokemon_center').click(() => {
                $('#pokemon_center_open').removeClass('hidden');
                let pokecenter_audio = document.getElementById("nivcity");
                pokecenter_audio.volume = .2;
                pokecenter_audio.play();

                $.ajax({
                    url: 'pokemon_center.php',
                    success: function(response) {
                        $('#pokemon_center_contain').html(response);
                    }
                })

            });
            $('#pokemon_center_close').click(() => {
                $('#pokemon_center_open').addClass('hidden');
                $('#display_draw_pokemon').empty();
                let pokecenter_audio = document.getElementById("nivcity");
                pokecenter_audio.pause();

            })
            // -------------------------END POKEMONCENTER------------------------
            // -------------------------START EVOLUTION_CENTER------------------------
            $('#evolution_center').click(() => {
                window.location.href = "evolutionCenter.php";
            })
            // -------------------------START ARENA------------------------
            $('#arena').click(() => {
                window.location.href = "battletest.php";
            })


            // -------------------------GO_TO_DUEGON------------------------
            $('#go_to_duegon').click(() => {
                window.location.href = "duegon_map.php";
            })
        });
    </script>
</body>

</html>