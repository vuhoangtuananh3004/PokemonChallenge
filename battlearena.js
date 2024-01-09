$(document).ready(function () {
  let battle_team = [];
  let pokedex_team = [];
  $.ajax({
    url: "pokemon_lab_action.php",
    type: "GET",
    data: {
      data: "get_user_pokedex",
    },
    success: function (response) {
      var data = JSON.parse(response);
      battle_team = data.filter((item) => item.battle_team === "TRUE");
      pokedex_team = data.filter((item) => item.battle_team === "FALSE");
      console.log("====================================");
      console.log(battle_team);
      console.log("====================================");
      var canvas = document.getElementById("battlecanvas");
      var ctx = canvas.getContext("2d");
      ctx.font = "20px Arial";

      var pokemon1 = 0;
      var pokemon2 = 0;

      function getCookie(cname) {
        var cnameE = cname + "=";
        var carray = document.cookie.split(";");
        var cookie = null;
        for (var i = 0; i < carray.length; i++) {
          var current = carray[i];
          while (current.charAt(0) == " ") {
            current = current.substring(1, current.length);
          }
          if (current.indexOf(cnameE) == 0) {
            cookie = current.substring(cnameE.length, current.length);
          }
        }
        return cookie;
      }

      //STARTING AND DEFAULT/CONST VARIABLES
      pokemon1 = getCookie("pokemon1");
      var pokemon1_name = battle_team[0].name;
      var pokemon1_lvl = battle_team[0].level;
      var pokemon1_hp = battle_team[0].hp;
      var pokemon1_type1 = battle_team[0].type1;
      var pokemon1_type2 = battle_team[0].type2;
      var pokemon1_atk = battle_team[0].atk;
      var pokemon1_def = battle_team[0].def;
      var pokemon1_spatk = battle_team[0].spatk;
      var pokemon1_spdef = battle_team[0].spdef;
      var pokemon1_speed = battle_team[0].speed;

      //all stats scaled using same scaling formula
      pokemon1_hp =
        parseInt(pokemon1_hp * (0.01 * parseInt(pokemon1_lvl))) +
        parseInt(pokemon1_lvl) +
        10;
      pokemon1_atk =
        parseInt(pokemon1_atk * (0.01 * parseInt(pokemon1_lvl))) +
        parseInt(pokemon1_lvl) +
        10;
      pokemon1_def =
        parseInt(pokemon1_def * (0.01 * parseInt(pokemon1_lvl))) +
        parseInt(pokemon1_lvl) +
        10;
      pokemon1_spatk =
        parseInt(pokemon1_spatk * (0.01 * parseInt(pokemon1_lvl))) +
        parseInt(pokemon1_lvl) +
        10;
      pokemon1_spdef =
        parseInt(pokemon1_spdef * (0.01 * parseInt(pokemon1_lvl))) +
        parseInt(pokemon1_lvl) +
        10;
      pokemon1_speed =
        parseInt(pokemon1_speed * (0.01 * parseInt(pokemon1_lvl))) +
        parseInt(pokemon1_lvl) +
        10;

      var pokemon2 = getCookie("pokemon2");
      var pokemon2_name = getCookie("pokemon2_name");
      var pokemon2_lvl = getCookie("pokemon2_lvl");
      var pokemon2_hp = getCookie("pokemon2_hp");
      var pokemon2_type1 = getCookie("pokemon2_type1");
      var pokemon2_type2 = getCookie("pokemon2_type2");
      var pokemon2_atk = getCookie("pokemon2_atk");
      var pokemon2_def = getCookie("pokemon2_def");
      var pokemon2_spatk = getCookie("pokemon2_spatk");
      var pokemon2_spdef = getCookie("pokemon2_spdef");
      var pokemon2_speed = getCookie("pokemon2_speed");

      //all stats scaled using same scaling formula
      pokemon2_hp =
        parseInt(pokemon2_hp * (0.01 * parseInt(pokemon2_lvl))) +
        parseInt(pokemon2_lvl) +
        10;
      pokemon2_atk =
        parseInt(pokemon2_atk * (0.01 * parseInt(pokemon2_lvl))) +
        parseInt(pokemon2_lvl) +
        10;
      pokemon2_def =
        parseInt(pokemon2_def * (0.01 * parseInt(pokemon2_lvl))) +
        parseInt(pokemon2_lvl) +
        10;
      pokemon2_spatk =
        parseInt(pokemon2_spatk * (0.01 * parseInt(pokemon2_lvl))) +
        parseInt(pokemon2_lvl) +
        10;
      pokemon2_spdef =
        parseInt(pokemon2_spdef * (0.01 * parseInt(pokemon2_lvl))) +
        parseInt(pokemon2_lvl) +
        10;
      pokemon2_speed =
        parseInt(pokemon2_speed * (0.01 * parseInt(pokemon2_lvl))) +
        parseInt(pokemon2_lvl) +
        10;

      //get moves for opposing pokemon

      var pokemon2_move1_name = getCookie("pokemon2_move1");
      var pokemon2_move1_type = getCookie("pokemon2_move1_type");
      var pokemon2_move1_damage = getCookie("pokemon2_move1_damage");
      var pokemon2_move1_power = getCookie("pokemon2_move1_power");
      var pokemon2_move1_accuracy = getCookie("pokemon2_move1_accuracy");

      var pokemon2_move2_name = getCookie("pokemon2_move2");
      var pokemon2_move2_type = getCookie("pokemon2_move2_type");
      var pokemon2_move2_damage = getCookie("pokemon2_move2_damage");
      var pokemon2_move2_power = getCookie("pokemon2_move2_power");
      var pokemon2_move2_accuracy = getCookie("pokemon2_move2_accuracy");

      var pokemon2_move3_name = getCookie("pokemon2_move3");
      var pokemon2_move3_type = getCookie("pokemon2_move3_type");
      var pokemon2_move3_damage = getCookie("pokemon2_move3_damage");
      var pokemon2_move3_power = getCookie("pokemon2_move3_power");
      var pokemon2_move3_accuracy = getCookie("pokemon2_move3_accuracy");

      var pokemon2_move4_name = getCookie("pokemon2_move4");
      var pokemon2_move4_type = getCookie("pokemon2_move4_type");
      var pokemon2_move4_damage = getCookie("pokemon2_move4_damage");
      var pokemon2_move4_power = getCookie("pokemon2_move4_power");
      var pokemon2_move4_accuracy = getCookie("pokemon2_move4_accuracy");

      var pokemon1_img = new Image();
      var pokemon2_img = new Image();
      var hpbar_img = new Image();
      var hpbar_opp_img = new Image();
      var battlefield = new Image();

      hpbar_img.src = "HPBAR.png";
      hpbar_opp_img.src = "HPBAR_OPP.png";
      battlefield.src = "battlefield.png";

      //generate image path from pokemon numbers
      pokemon1_img.src = battle_team[0].data;


      if (pokemon2 < 10) {
        pokemon2_img.src = "pokemon_images/00" + pokemon2 + ".png";
      } else if (pokemon2 < 100) {
        pokemon2_img.src = "pokemon_images/0" + pokemon2 + ".png";
      } else {
        pokemon2_img.src = "pokemon_images/" + pokemon2 + ".png";
      }

      //BATTLE VARIABLES
      var pokemon1_currenthp = pokemon1_hp;
      var pokemon2_currenthp = pokemon2_hp;
      var current_turn = 0;
      var BATTLE_OVER = false;
      var BATTLE_RESULT = null; //will be set to true for win or false for loss

      function turn(name, type, move_type, power, accuracy) {
        if (!BATTLE_OVER) {
          current_turn = current_turn + 1;
          document.getElementById("turn_stats").innerHTML =
            "TURN " + current_turn + ": <br> ";
          if (pokemon1_speed >= pokemon2_speed) {
            document.getElementById("turn_stats").innerHTML +=
              pokemon1_name + " goes first.<br>";
            document.getElementById("turn_stats").innerHTML +=
              pokemon1_name + " uses " + name;

            //USER
            if (move_type == "physical") {
              damage = Math.floor(
                parseInt(
                  ((2 * parseInt(pokemon1_lvl)) / 10 + 2) *
                    power *
                    (pokemon1_atk / pokemon2_def)
                ) /
                  50 +
                  2
              );
            } else {
              damage = Math.floor(
                parseInt(
                  ((2 * parseInt(pokemon1_lvl)) / 10 + 2) *
                    power *
                    (pokemon1_spatk / pokemon2_spdef)
                ) /
                  50 +
                  2
              );
            }

            //determine if the move hits or misses
            if (Math.random() * 100 < accuracy) {
              if (pokemon2_currenthp - damage < 0) {
                pokemon2_currenthp = 0;
              } else {
                pokemon2_currenthp = pokemon2_currenthp - damage;
              }
              document.getElementById("turn_stats").innerHTML +=
                " and does " + damage + " damage. <br>";
            } else {
              document.getElementById("turn_stats").innerHTML +=
                "but it missed! <br>";
            }

            if (pokemon2_currenthp == 0) {
              BATTLE_RESULT = true;
              BATTLE_OVER = true;
              document.getElementById("turn_stats").innerHTML += "<br>YOU WIN!";
              //TODO: UPDATE USER_DEX
            } else {
              //OPPONENT
              //randomy selects move that opponent will select
              var temp = Math.floor(Math.random() * 3 + 1);

              switch (temp) {
                case 1:
                  var opp_move_name = pokemon2_move1_name.replace("%20", " ");
                  var opp_move_type = pokemon2_move1_damage;
                  power = pokemon2_move1_power;
                  accuracy = pokemon2_move1_accuracy;
                  break;
                case 2:
                  var opp_move_name = pokemon2_move2_name.replace("%20", " ");
                  var opp_move_type = pokemon2_move2_damage;
                  power = pokemon2_move2_power;
                  accuracy = pokemon2_move2_accuracy;
                  break;
                case 3:
                  var opp_move_name = pokemon2_move3_name.replace("%20", " ");
                  var opp_move_type = pokemon2_move3_damage;
                  power = pokemon2_move3_power;
                  accuracy = pokemon2_move3_accuracy;
                  break;
                case 4:
                  var opp_move_name = pokemon2_move4_name.replace("%20", " ");
                  var opp_move_type = pokemon2_move4_damage;
                  power = pokemon2_move4_power;
                  accuracy = pokemon2_move4_accuracy;
                  break;
              }

              document.getElementById("turn_stats").innerHTML +=
                pokemon2_name + " uses " + opp_move_name;

              if (opp_move_type == "physical") {
                damage = Math.floor(
                  parseInt(
                    ((2 * parseInt(pokemon2_lvl)) / 10 + 2) *
                      power *
                      (pokemon2_atk / pokemon1_def)
                  ) /
                    50 +
                    2
                );
              } else {
                damage = Math.floor(
                  parseInt(
                    ((2 * parseInt(pokemon2_lvl)) / 10 + 2) *
                      power *
                      (pokemon2_spatk / pokemon1_spdef)
                  ) /
                    50 +
                    2
                );
              }

              //determine if the move hits or misses
              if (Math.random() * 100 < accuracy) {
                if (pokemon1_currenthp - damage < 0) {
                  pokemon1_currenthp = 0;
                } else {
                  pokemon1_currenthp = pokemon1_currenthp - damage;
                }
                document.getElementById("turn_stats").innerHTML +=
                  " and does " + damage + " damage. <br>";
              } else {
                document.getElementById("turn_stats").innerHTML +=
                  "but it missed! <br>";
              }

              if (pokemon1_currenthp == 0) {
                BATTLE_RESULT = false;
                BATTLE_OVER = true;
                document.getElementById("turn_stats").innerHTML +=
                  "<br>OPPONENT WINS!";
              }
            }
          } else {
            document.getElementById("turn_stats").innerHTML +=
              pokemon2_name + " goes first.<br>";
            //OPPONENT
            //randomy selects move that opponent will select
            var temp = Math.floor(Math.random() * 3 + 1);

            switch (temp) {
              case 1:
                var opp_move_name = pokemon2_move1_name.replace("%20", " ");
                var opp_move_type = pokemon2_move1_damage;
                power = pokemon2_move1_power;
                accuracy = pokemon2_move1_accuracy;
                break;
              case 2:
                var opp_move_name = pokemon2_move2_name.replace("%20", " ");
                var opp_move_type = pokemon2_move2_damage;
                power = pokemon2_move2_power;
                accuracy = pokemon2_move2_accuracy;
                break;
              case 3:
                var opp_move_name = pokemon2_move3_name.replace("%20", " ");
                var opp_move_type = pokemon2_move3_damage;
                power = pokemon2_move3_power;
                accuracy = pokemon2_move3_accuracy;
                break;
              case 4:
                var opp_move_name = pokemon2_move4_name.replace("%20", " ");
                var opp_move_type = pokemon2_move4_damage;
                power = pokemon2_move4_power;
                accuracy = pokemon2_move4_accuracy;
                break;
            }

            document.getElementById("turn_stats").innerHTML +=
              pokemon2_name + " uses " + opp_move_name;

            if (move_type == "physical" || move_type == "Physical") {
              damage = Math.floor(
                parseInt(
                  ((2 * parseInt(pokemon2_lvl)) / 10 + 2) *
                    power *
                    (pokemon2_atk / pokemon1_def)
                ) /
                  50 +
                  2
              );
            } else {
              damage = Math.floor(
                parseInt(
                  ((2 * parseInt(pokemon2_lvl)) / 10 + 2) *
                    power *
                    (pokemon2_spatk / pokemon1_spdef)
                ) /
                  50 +
                  2
              );
            }

            //determine if the move hits or misses
            if (Math.random() * 100 < accuracy) {
              if (pokemon1_currenthp - damage < 0) {
                pokemon1_currenthp = 0;
              } else {
                pokemon1_currenthp = pokemon1_currenthp - damage;
              }
              document.getElementById("turn_stats").innerHTML +=
                " and does " + damage + " damage. <br>";
            } else {
              document.getElementById("turn_stats").innerHTML +=
                "but it missed! <br>";
            }

            if (pokemon1_currenthp == 0) {
              BATTLE_RESULT = false;
              BATTLE_OVER = true;
              document.getElementById("turn_stats").innerHTML +=
                "<br>OPPONENT WINS!";
            } else {
              document.getElementById("turn_stats").innerHTML +=
                pokemon1_name + " uses " + name;

              //USER
              if (move_type == "physical" || move_type == "Physical") {
                damage = Math.floor(
                  parseInt(
                    ((2 * parseInt(pokemon1_lvl)) / 10 + 2) *
                      power *
                      (pokemon1_atk / pokemon2_def)
                  ) /
                    50 +
                    2
                );
              } else {
                damage = Math.floor(
                  parseInt(
                    ((2 * parseInt(pokemon1_lvl)) / 10 + 2) *
                      power *
                      (pokemon1_spatk / pokemon2_spdef)
                  ) /
                    50 +
                    2
                );
              }

              //determine if the move hits or misses
              if (Math.random() * 100 < accuracy) {
                if (pokemon2_currenthp - damage < 0) {
                  pokemon2_currenthp = 0;
                } else {
                  pokemon2_currenthp = pokemon2_currenthp - damage;
                }
                document.getElementById("turn_stats").innerHTML +=
                  " and does " + damage + " damage. <br>";
              } else {
                document.getElementById("turn_stats").innerHTML +=
                  "but it missed! <br>";
              }
              if (pokemon2_currenthp == 0) {
                BATTLE_RESULT = true;
                BATTLE_OVER = true;
                document.getElementById("turn_stats").innerHTML +=
                  "<br>YOU WIN!";
                //TODO: UPDATE USER_DEX
              }
            }
          }
        }
      }

      //roughly 60 fps, use frame variable to adjust speed/timing of events
      var frame = 0;

      //animation variables
      var hpbar1 = 260;
      var hpbar2 = 260;

      window.requestAnimationFrame(draw);

      function draw() {
        //BACKGROUND
        ctx.drawImage(battlefield, 0, 0, 800, 600);

        //PLAYER
        ctx.fillStyle = "White";
        ctx.fillRect(55, 310, 260, 20);
        if (pokemon1_currenthp < pokemon1_hp / 5) {
          ctx.fillStyle = "Red";
        } else if (pokemon1_currenthp < pokemon1_hp / 2) {
          ctx.fillStyle = "Yellow";
        } else {
          ctx.fillStyle = "LightGreen";
        }
        var hpbar1target = Math.floor(260 * (pokemon1_currenthp / pokemon1_hp));
        if (hpbar1 != hpbar1target) {
          var hpspeed1 = 3;
          if (hpbar1 - hpbar1target < 3 || hpbar1 - hpbar1target < 3) {
            hpspeed1 = 1;
          }
          if (hpbar1 > hpbar1target) {
            hpbar1 = hpbar1 - hpspeed1;
          } else {
            hpbar1 = hpbar1 + hpspeed1;
          }
        }
        ctx.fillRect(55, 310, hpbar1, 20);
        ctx.fillStyle = "black";
     

        ctx.drawImage(pokemon1_img, 50, 400, 200, 200);
        ctx.drawImage(hpbar_img, 0, 275);
        ctx.fillText(pokemon1_currenthp + "/" + pokemon1_hp, 60, 355);
        ctx.fillText(pokemon1_name, 30, 305);
        ctx.fillText(pokemon1_lvl, 270, 310);

        //OPPONENT
        ctx.fillStyle = "White";
        ctx.fillRect(530, 92, 260, 20);
        if (pokemon2_currenthp < pokemon2_hp / 5) {
          ctx.fillStyle = "Red";
        } else if (pokemon2_currenthp < pokemon2_hp / 2) {
          ctx.fillStyle = "Yellow";
        } else {
          ctx.fillStyle = "LightGreen";
        }
        var hpbar2target = Math.floor(260 * (pokemon2_currenthp / pokemon2_hp));
        if (hpbar2 != hpbar2target) {
          var hpspeed2 = 3;
          if (hpbar2 - hpbar2target < 3 || hpbar2 - hpbar2target < 3) {
            hpspeed2 = 1;
          }
          if (hpbar2 > hpbar2target) {
            hpbar2 = hpbar2 - hpspeed2;
          } else {
            hpbar2 = hpbar2 + hpspeed2;
          }
        }
        ctx.fillRect(530, 92, hpbar2, 20);
        ctx.fillStyle = "black";

        ctx.drawImage(pokemon2_img, 575, 175);
        ctx.drawImage(hpbar_opp_img, 475, 50);
        ctx.fillText(pokemon2_currenthp + "/" + pokemon2_hp, 535, 130);
        ctx.fillText(pokemon2_name, 505, 80);
        ctx.fillText(pokemon2_lvl, 735, 85);

        //cycles frame number from 0 to 60 so it can be used to adjust speed of animations
        if (frame == 60) {
          frame = 0;
        } else {
          frame = frame + 1;
        }

        window.requestAnimationFrame(draw);
      }
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
});

// var canvas = document.getElementById("battlecanvas");
// var ctx = canvas.getContext("2d");
// ctx.font = "20px Arial";

// var pokemon1 = 0;
// var pokemon2 = 0;

// function getCookie(cname){
//     var cnameE = cname + "=";
//     var carray = document.cookie.split(";");
//     var cookie = null;
//     for (var i = 0; i<carray.length; i++){
//         var current = carray[i];
//         while (current.charAt(0)==' '){
//             current = current.substring(1,current.length);
//         }
//         if (current.indexOf(cnameE) == 0) {
//             cookie = current.substring(cnameE.length,current.length);
//         }
//     }
//     return cookie;
// }

// //STARTING AND DEFAULT/CONST VARIABLES
// pokemon1 = getCookie('pokemon1');
// var pokemon1_name = getCookie('pokemon1_name');
// var pokemon1_lvl = getCookie('pokemon1_lvl');
// var pokemon1_hp = getCookie('pokemon1_hp');
// var pokemon1_type1 = getCookie('pokemon1_type1');
// var pokemon1_type2 = getCookie('pokemon1_type2');
// var pokemon1_atk = getCookie('pokemon1_atk');
// var pokemon1_def = getCookie('pokemon1_def');
// var pokemon1_spatk = getCookie('pokemon1_spatk');
// var pokemon1_spdef = getCookie('pokemon1_spdef');
// var pokemon1_speed = getCookie('pokemon1_speed');

// //all stats scaled using same scaling formula
// pokemon1_hp = parseInt((pokemon1_hp) * (.01 * parseInt(pokemon1_lvl))) + parseInt(pokemon1_lvl) + 10;
// pokemon1_atk =  parseInt((pokemon1_atk) * (.01 * parseInt(pokemon1_lvl))) + parseInt(pokemon1_lvl) + 10;
// pokemon1_def = parseInt((pokemon1_def) * (.01 * parseInt(pokemon1_lvl))) + parseInt(pokemon1_lvl) + 10;
// pokemon1_spatk = parseInt((pokemon1_spatk) * (.01 * parseInt(pokemon1_lvl))) + parseInt(pokemon1_lvl) + 10;
// pokemon1_spdef = parseInt((pokemon1_spdef) * (.01 * parseInt(pokemon1_lvl))) + parseInt(pokemon1_lvl) + 10;
// pokemon1_speed = parseInt((pokemon1_speed) * (.01 * parseInt(pokemon1_lvl))) + parseInt(pokemon1_lvl) + 10;

// var pokemon2 = getCookie('pokemon2');
// var pokemon2_name = getCookie('pokemon2_name');
// var pokemon2_lvl = getCookie('pokemon2_lvl');
// var pokemon2_hp = getCookie('pokemon2_hp');
// var pokemon2_type1 = getCookie('pokemon2_type1');
// var pokemon2_type2 = getCookie('pokemon2_type2');
// var pokemon2_atk = getCookie('pokemon2_atk');
// var pokemon2_def = getCookie('pokemon2_def');
// var pokemon2_spatk = getCookie('pokemon2_spatk');
// var pokemon2_spdef = getCookie('pokemon2_spdef');
// var pokemon2_speed = getCookie('pokemon2_speed');

// //all stats scaled using same scaling formula
// pokemon2_hp = parseInt((pokemon2_hp) * (.01 * parseInt(pokemon2_lvl))) + parseInt(pokemon2_lvl) + 10;
// pokemon2_atk = parseInt((pokemon2_atk) * (.01 * parseInt(pokemon2_lvl))) + parseInt(pokemon2_lvl) + 10;
// pokemon2_def = parseInt((pokemon2_def) * (.01 * parseInt(pokemon2_lvl))) + parseInt(pokemon2_lvl) + 10;
// pokemon2_spatk = parseInt((pokemon2_spatk) * (.01 * parseInt(pokemon2_lvl))) + parseInt(pokemon2_lvl) + 10;
// pokemon2_spdef = parseInt((pokemon2_spdef) * (.01 * parseInt(pokemon2_lvl))) + parseInt(pokemon2_lvl) + 10;
// pokemon2_speed = parseInt((pokemon2_speed) * (.01 * parseInt(pokemon2_lvl))) + parseInt(pokemon2_lvl) + 10;

// //get moves for opposing pokemon

// var pokemon2_move1_name = getCookie('pokemon2_move1');
// var pokemon2_move1_type = getCookie('pokemon2_move1_type');
// var pokemon2_move1_damage = getCookie('pokemon2_move1_damage');
// var pokemon2_move1_power = getCookie('pokemon2_move1_power');
// var pokemon2_move1_accuracy = getCookie('pokemon2_move1_accuracy');

// var pokemon2_move2_name = getCookie('pokemon2_move2');
// var pokemon2_move2_type = getCookie('pokemon2_move2_type');
// var pokemon2_move2_damage = getCookie('pokemon2_move2_damage');
// var pokemon2_move2_power = getCookie('pokemon2_move2_power');
// var pokemon2_move2_accuracy = getCookie('pokemon2_move2_accuracy');

// var pokemon2_move3_name = getCookie('pokemon2_move3');
// var pokemon2_move3_type = getCookie('pokemon2_move3_type');
// var pokemon2_move3_damage = getCookie('pokemon2_move3_damage');
// var pokemon2_move3_power = getCookie('pokemon2_move3_power');
// var pokemon2_move3_accuracy = getCookie('pokemon2_move3_accuracy');

// var pokemon2_move4_name = getCookie('pokemon2_move4');
// var pokemon2_move4_type = getCookie('pokemon2_move4_type');
// var pokemon2_move4_damage = getCookie('pokemon2_move4_damage');
// var pokemon2_move4_power = getCookie('pokemon2_move4_power');
// var pokemon2_move4_accuracy = getCookie('pokemon2_move4_accuracy');

// var pokemon1_img = new Image();
// var pokemon2_img = new Image();
// var hpbar_img = new Image();
// var hpbar_opp_img = new Image();
// var battlefield = new Image();

// hpbar_img.src = "HPBAR.png";
// hpbar_opp_img.src = "HPBAR_OPP.png";
// battlefield.src = "battlefield.png";

// //generate image path from pokemon numbers
// if (pokemon1 < 10){
//     pokemon1_img.src = "pokemon_images/00" + pokemon1 + ".png";
// }
// else if (pokemon1 < 100){
//     pokemon1_img.src = "pokemon_images/0" + pokemon1 + ".png";
// }
// else{
//     pokemon1_img.src = "pokemon_images/" + pokemon1 + ".png";
// }
// if (pokemon2 < 10){
//     pokemon2_img.src = "pokemon_images/00" + pokemon2 + ".png";
// }
// else if (pokemon2 < 100){
//     pokemon2_img.src = "pokemon_images/0" + pokemon2 + ".png";
// }
// else{
//     pokemon2_img.src = "pokemon_images/" + pokemon2 + ".png";
// }

// //BATTLE VARIABLES
// var pokemon1_currenthp = pokemon1_hp;
// var pokemon2_currenthp = pokemon2_hp;
// var current_turn = 0;
// var BATTLE_OVER = false;
// var BATTLE_RESULT = null; //will be set to true for win or false for loss

// function turn(name,type,move_type,power,accuracy){

//     if (!BATTLE_OVER){
//         current_turn = current_turn + 1;
//         document.getElementById('turn_stats').innerHTML = "TURN "+ current_turn + ": <br> " ;
//         if (pokemon1_speed >= pokemon2_speed){

//             document.getElementById('turn_stats').innerHTML += pokemon1_name + " goes first.<br>" ;
//             document.getElementById('turn_stats').innerHTML += pokemon1_name + " uses " + name ;

//             //USER
//             if (move_type == "physical"){
//                 damage = Math.floor(  (parseInt((((2*parseInt(pokemon1_lvl))/10)+2)*power*(pokemon1_atk/pokemon2_def))/50)+2 );
//             }
//             else {
//                 damage = Math.floor( (parseInt((((2*parseInt(pokemon1_lvl))/10)+2)*power*(pokemon1_spatk/pokemon2_spdef))/50)+2 );
//             }

//             //determine if the move hits or misses
//             if ((Math.random()*100) < accuracy){
//                 if ((pokemon2_currenthp - damage) < 0){
//                     pokemon2_currenthp = 0;
//                 }
//                 else{
//                     pokemon2_currenthp = pokemon2_currenthp - damage;
//                 }
//                 document.getElementById('turn_stats').innerHTML += " and does " + damage + " damage. <br>";
//             }
//             else{
//                 document.getElementById('turn_stats').innerHTML += "but it missed! <br>";
//             }

//             if (pokemon2_currenthp == 0){
//                 BATTLE_RESULT = true;
//                 BATTLE_OVER = true;
//                 document.getElementById('turn_stats').innerHTML += "<br>YOU WIN!";
//                 //TODO: UPDATE USER_DEX
//             }
//             else{
//                 //OPPONENT
//                 //randomy selects move that opponent will select
//                 var temp = Math.floor((Math.random()*3)+1);

//                 switch(temp){
//                     case 1:
//                         var opp_move_name = pokemon2_move1_name.replace('%20',' ');
//                         var opp_move_type = pokemon2_move1_damage;
//                         power = pokemon2_move1_power;
//                         accuracy = pokemon2_move1_accuracy;
//                         break;
//                     case 2:
//                         var opp_move_name = pokemon2_move2_name.replace('%20',' ');
//                         var opp_move_type = pokemon2_move2_damage;
//                         power = pokemon2_move2_power;
//                         accuracy = pokemon2_move2_accuracy;
//                         break;
//                     case 3:
//                         var opp_move_name = pokemon2_move3_name.replace('%20',' ');
//                         var opp_move_type = pokemon2_move3_damage;
//                         power = pokemon2_move3_power;
//                         accuracy = pokemon2_move3_accuracy;
//                         break;
//                     case 4:
//                         var opp_move_name = pokemon2_move4_name.replace('%20',' ');
//                         var opp_move_type = pokemon2_move4_damage;
//                         power = pokemon2_move4_power;
//                         accuracy = pokemon2_move4_accuracy;
//                         break;
//                 }

//                 document.getElementById('turn_stats').innerHTML += pokemon2_name + " uses " + opp_move_name;

//                 if (opp_move_type == "physical"){
//                     damage = Math.floor( (parseInt((((2*parseInt(pokemon2_lvl))/10)+2)*power*(pokemon2_atk/pokemon1_def))/50)+2 );
//                 }
//                 else {
//                     damage = Math.floor( (parseInt((((2*parseInt(pokemon2_lvl))/10)+2)*power*(pokemon2_spatk/pokemon1_spdef))/50)+2 );
//                 }

//                 //determine if the move hits or misses
//                 if ((Math.random()*100) < accuracy){
//                     if ((pokemon1_currenthp - damage) < 0){
//                         pokemon1_currenthp = 0;
//                     }
//                     else{
//                         pokemon1_currenthp = pokemon1_currenthp - damage;
//                     }
//                     document.getElementById('turn_stats').innerHTML += " and does " + damage + " damage. <br>";
//                 }
//                 else{
//                     document.getElementById('turn_stats').innerHTML += "but it missed! <br>";
//                 }

//                 if (pokemon1_currenthp == 0){
//                     BATTLE_RESULT = false;
//                     BATTLE_OVER = true;
//                     document.getElementById('turn_stats').innerHTML += "<br>OPPONENT WINS!"
//                 }

//             }

//         }
//         else{

//             document.getElementById('turn_stats').innerHTML += pokemon2_name + " goes first.<br>" ;
//             //OPPONENT
//             //randomy selects move that opponent will select
//             var temp = Math.floor((Math.random()*3)+1);

//             switch(temp){
//                 case 1:
//                     var opp_move_name = pokemon2_move1_name.replace('%20',' ');
//                     var opp_move_type = pokemon2_move1_damage;
//                     power = pokemon2_move1_power;
//                     accuracy = pokemon2_move1_accuracy;
//                     break;
//                 case 2:
//                     var opp_move_name = pokemon2_move2_name.replace('%20',' ');
//                     var opp_move_type = pokemon2_move2_damage;
//                     power = pokemon2_move2_power;
//                     accuracy = pokemon2_move2_accuracy;
//                     break;
//                 case 3:
//                     var opp_move_name = pokemon2_move3_name.replace('%20',' ');
//                     var opp_move_type = pokemon2_move3_damage;
//                     power = pokemon2_move3_power;
//                     accuracy = pokemon2_move3_accuracy;
//                     break;
//                 case 4:
//                     var opp_move_name = pokemon2_move4_name.replace('%20',' ');
//                     var opp_move_type = pokemon2_move4_damage;
//                     power = pokemon2_move4_power;
//                     accuracy = pokemon2_move4_accuracy;
//                     break;
//             }

//             document.getElementById('turn_stats').innerHTML += pokemon2_name + " uses " + opp_move_name;

//             if (move_type == "physical" || move_type == "Physical"){
//                 damage = Math.floor( (parseInt((((2*parseInt(pokemon2_lvl))/10)+2)*power*(pokemon2_atk/pokemon1_def))/50)+2 );
//             }
//             else {
//                 damage = Math.floor(  (parseInt((((2*parseInt(pokemon2_lvl))/10)+2)*power*(pokemon2_spatk/pokemon1_spdef))/50)+2 );
//             }

//             //determine if the move hits or misses
//             if ((Math.random()*100) < accuracy){
//                 if ((pokemon1_currenthp - damage) < 0){
//                     pokemon1_currenthp = 0;
//                 }
//                 else{
//                     pokemon1_currenthp = pokemon1_currenthp - damage;
//                 }
//                 document.getElementById('turn_stats').innerHTML += " and does " + damage + " damage. <br>";
//             }
//             else{
//                 document.getElementById('turn_stats').innerHTML += "but it missed! <br>";
//             }

//             if (pokemon1_currenthp == 0){
//                 BATTLE_RESULT = false;
//                 BATTLE_OVER = true;
//                 document.getElementById('turn_stats').innerHTML += "<br>OPPONENT WINS!"
//             }
//             else{
//                 document.getElementById('turn_stats').innerHTML += pokemon1_name + " uses " + name ;

//                 //USER
//                 if (move_type == "physical" || move_type == "Physical"){
//                     damage = Math.floor( (parseInt((((2*parseInt(pokemon1_lvl))/10)+2)*power*(pokemon1_atk/pokemon2_def))/50)+2 );
//                 }
//                 else {
//                     damage = Math.floor( (parseInt((((2*parseInt(pokemon1_lvl))/10)+2)*power*(pokemon1_spatk/pokemon2_spdef))/50)+2 );
//                 }

//                 //determine if the move hits or misses
//                 if ((Math.random()*100) < accuracy){
//                     if ((pokemon2_currenthp - damage) < 0){
//                         pokemon2_currenthp = 0;
//                     }
//                     else{
//                         pokemon2_currenthp = pokemon2_currenthp - damage;
//                     }
//                     document.getElementById('turn_stats').innerHTML += " and does " + damage + " damage. <br>";
//                 }
//                 else{
//                     document.getElementById('turn_stats').innerHTML += "but it missed! <br>";
//                 }
//                 if (pokemon2_currenthp == 0){
//                     BATTLE_RESULT = true;
//                     BATTLE_OVER = true;
//                     document.getElementById('turn_stats').innerHTML += "<br>YOU WIN!";
//                     //TODO: UPDATE USER_DEX
//                 }

//             }

//         }

//     }

// }

// //roughly 60 fps, use frame variable to adjust speed/timing of events
// var frame = 0;

// //animation variables
// var hpbar1 = 260;
// var hpbar2 = 260;

// window.requestAnimationFrame(draw);

// function draw(){
//     //BACKGROUND
//     ctx.drawImage(battlefield, 0, 0, 800, 600);

//     //PLAYER
//     ctx.fillStyle = "White";
//     ctx.fillRect(55, 310, 260, 20);
//     if (pokemon1_currenthp < (pokemon1_hp/5)){
//         ctx.fillStyle = "Red";
//     }
//     else if (pokemon1_currenthp < (pokemon1_hp/2)){
//         ctx.fillStyle = "Yellow";
//     }
//     else {
//         ctx.fillStyle = "LightGreen";
//     }
//     var hpbar1target = Math.floor(260*(pokemon1_currenthp/pokemon1_hp))
//     if (hpbar1 != hpbar1target){
//         var hpspeed1 = 3;
//         if ((hpbar1 - hpbar1target < 3) || (hpbar1 - hpbar1target < 3)){
//             hpspeed1 = 1;
//         }
//         if (hpbar1 > hpbar1target){

//             hpbar1 = hpbar1 - hpspeed1;
//         }
//         else {
//             hpbar1 = hpbar1 + hpspeed1;
//         }
//     }
//     ctx.fillRect(55, 310, hpbar1, 20);
//     ctx.fillStyle = "black";

//     ctx.drawImage(pokemon1_img, 50, 400, 200, 200);
//     ctx.drawImage(hpbar_img, 0, 275);
//     ctx.fillText(pokemon1_currenthp + '/' + pokemon1_hp, 60, 355);
//     ctx.fillText(pokemon1_name, 30, 305);
//     ctx.fillText(pokemon1_lvl, 270, 310);

//     //OPPONENT
//     ctx.fillStyle = "White";
//     ctx.fillRect(530, 92, 260, 20);
//     if (pokemon2_currenthp < (pokemon2_hp/5)){
//         ctx.fillStyle = "Red";
//     }
//     else if (pokemon2_currenthp < (pokemon2_hp/2)){
//         ctx.fillStyle = "Yellow";
//     }
//     else {
//         ctx.fillStyle = "LightGreen";
//     }
//     var hpbar2target = Math.floor(260*(pokemon2_currenthp/pokemon2_hp))
//     if (hpbar2 != hpbar2target){
//         var hpspeed2 = 3;
//         if ((hpbar2 - hpbar2target < 3) || (hpbar2 - hpbar2target < 3)){
//             hpspeed2 = 1;
//         }
//         if (hpbar2 > hpbar2target){

//             hpbar2 = hpbar2 - hpspeed2;
//         }
//         else {
//             hpbar2 = hpbar2 + hpspeed2;
//         }

//     }
//     ctx.fillRect(530, 92, hpbar2, 20);
//     ctx.fillStyle = "black";

//     ctx.drawImage(pokemon2_img, 575, 175);
//     ctx.drawImage(hpbar_opp_img, 475, 50);
//     ctx.fillText(pokemon2_currenthp + '/' + pokemon2_hp, 535, 130);
//     ctx.fillText(pokemon2_name, 505, 80);
//     ctx.fillText(pokemon2_lvl, 735, 85);

//     //cycles frame number from 0 to 60 so it can be used to adjust speed of animations
//     if(frame == 60){
//         frame = 0;
//     }
//     else{
//         frame = frame+1;
//     }

//     window.requestAnimationFrame(draw);
// }
