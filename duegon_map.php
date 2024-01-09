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
    <title>Duegon Map</title>
    <link rel="stylesheet" href="duegon_map.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

</head>

<body>
    <div class="relative h-screen w-screen bg-cover bg-no-repeat bg-center text-white overflow-hidden" id="duegon_map" style="background-image: url(images/duegonImg/duegon.jpg)">
        <div class="absolute hidden left-0 top-0 h-full w-full z-10" id="battle_field">
       
            <div class="flex flex-col h-full w-full justify-center items-center bg-black/70 rounded-md">
                <!-- SKILL USE THIS DIV -->
                <div class="relative h-[800px] w-[1200px] bg-contain bg-no-repeat bg-center z-20" style="background-image: url(images/arena.jpg)">
                            <!-- TURN -->
                            <div class="absolute h-[45px] w-[153px] top-[45px] left-[50px] bg-cover bg-no-repeat bg-center opacity-50" style="background-image: url(images/skill/turn.gif)"></div>
                            <div class="absolute h-[50px] top-[50px] left-[50px] text-[36px] font-bold text-white drop-shadow-xl">ROUND <span id="round_number">1</span></div>
                    <!-- NORMAL ATTACK -->
                    <div id="normal_atk">
                        <div class="absolute h-[50px] w-[50px] bottom-[45px] left-[615px]  hover:bg-rose-600/80 bg-cover bg-no-repeat bg-white bg-center rounded-full drop-shadow-2xl cursor-pointer z-30" style="background-image: url(images/skill/attack.png)"></div>
                        <div class="absolute h-[80px] w-[80px] bottom-[30px] left-[600px]  hover:bg-rose-600/80 bg-contain bg-no-repeat bg-center rounded-full drop-shadow-2xl cursor-pointer" style="background-image: url(images/skill/circle.gif)"></div>
                    </div>
                    <!-- SKILL 1 ATTACK -->
                    <div id="skill1_atk">
                        <div class="absolute h-[50px] w-[50px] bottom-[45px] left-[715px]  hover:bg-rose-600/80 bg-cover bg-no-repeat bg-white bg-center rounded-full drop-shadow-2xl cursor-pointer z-30" style="background-image: url(images/skill/turn.gif)"></div>
                        <div class="absolute h-[80px] w-[80px] bottom-[30px] left-[700px]  hover:bg-rose-600/80 bg-contain bg-no-repeat bg-center rounded-full drop-shadow-2xl cursor-pointer" style="background-image: url(images/skill/circle.gif)"></div>
                    </div>
                    <!-- <div class="absolute h-[70px] w-[70px] bottom-[50px] left-[600px]"> -->

                    <!-- </div> -->

                    <!-- END NORMAL ATTACK -->
                    <!-- ENEMY AND PLAYER USE THIS -->
                    <div class="flex flex-row h-full w-full">
                        <div class="absolute top-0 left-0 h-full w-full bg-black/30 z-30 animate__animated animate__bounceInUp hidden" id="change_round">
                            <div class="flex flex-col h-full w-full justify-center items-center text-[48px] text-white font-bold"><div><span id="change_round_num"></span></div>
                            <div class="h-[200px] w-[200px] bg-cover bg-no-repeat bg-center rounded-full drop-shadow-2xl cursor-pointer z-30" style="background-image: url(images/skill/attack.png)"></div>
                        </div>
                        </div>
                        <!-- PLAYER -->
                        <div class="relative h-full w-[600px] " id="player">
                                
                        </div>
                        <!-- ENEMY -->
                        <div class="relative h-full w-[600px] " id="enemy">
                        <div id="skill1_play" class="absolute top-10 right-[50px] h-[400px] w-[500px] bg-cover bg-no-repeat bg-center hidden" style="background-image: url(images/skill/turn.gif)"></div>
                        </div>
                    </div>
                    <div class="flex flex-row justify-center items-center w-full space-x-5 p-3 bg-white/30">
                        <div class="flex justify-center items-center h-[50px] w-[150px]  bg-green-500 rounded-full cursor-pointer" id="start_battle">
                            START BATTLE</div>
                        <div class="flex justify-center items-center  h-[50px] w-[150px] bg-red-500 rounded-full cursor-pointer" id="close_battle">
                            CLOSE</div>
                    </div>
                </div>

            </div>
        </div>
        <script type="module" src="duegon_map.js"></script>
</body>

</html>