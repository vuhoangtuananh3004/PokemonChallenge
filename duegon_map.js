import * as dms from "./duegon_map_support.js";

let result_attacker_stat;
let result_defender_stat;

let battle_start = false;
let battle_turn = 1;

let player_atk;
let player_def;

let player_poke;
let random_player_def = [0, 1, 2, 3, 4];
let player_survival_list = [0, 1, 2, 3, 4];

let enemy_poke;
let random_enemy_fight = [0, 1, 2, 3, 4];
let enemy_survival_list = [0, 1, 2, 3, 4];

const battle = (player_atk, player_def) => {
  let attacker = player_atk;
  let defender = player_def;
  let spAtkNum = dms.random_spc();
  let spDefNum = dms.random_spc();
  let atk = attacker.atk;
  let def = defender.def;
  let damge;
  let hp = defender.hp;

  atk = spAtkNum == 2 ? Math.ceil(atk + attacker.spatk * 0.2) : atk;
  def = spDefNum == 2 ? Math.ceil(def + defender.spdef * 0.2) : def;
  damge = atk - def;
  if (damge > 0) {
    hp -= damge;
    if (hp <= 0) {
      hp = 0;
    }
  } else {
    damge = 0;
  }
  defender.hp = hp;
  return { id: defender.id, hp: defender.hp };
};
const aoeBattle = (player_atk, player_def) => {
  let attacker = player_atk;
  let defenders = player_def;

  for (const defender of defenders) {
    let spAtkNum = dms.random_spc();
    let spDefNum = dms.random_spc();
    let atk = attacker.atk - 5;
    let def = defender.def;
    let damge;
    let hp = defender.hp;

    atk = spAtkNum == 2 ? Math.ceil(atk + attacker.spatk * 0.2) : atk;
    def = spDefNum == 2 ? Math.ceil(def + defender.spdef * 0.2) : def;
    damge = atk - def;
    if (damge > 0) {
      hp -= damge;
      if (hp <= 0) {
        hp = 0;
      }
    } else {
      damge = 0;
    }
    defender.hp = hp;
  }
  return defenders;
};
const delay = (second) => {
  return new Promise((resolve) => setTimeout(resolve, second * 1000));
};
const battle_status = (player_survival_list, enemy_survival_list) => {
  if (player_survival_list.length == 0) return "YOU LOSE";
  if (enemy_survival_list.length == 0) return "YOU WIN";
};
const check_if_pokemon_is_death = (deathList, aliveList) => {};
const resetSelectPokeForBattle = () => {
  $('[id^="player_poke_"]').removeClass(
    "drop-shadow-[0px_5px_10px_rgba(45,242,0,0.8)]"
  );
  $('[id^="enemy_poke_"]').removeClass(
    "drop-shadow-[0px_5px_10px_rgba(242,25,0,0.8)]"
  );
};
$(document).ready(function () {
  $("#skill1_atk").click(() => {
    resetSelectPokeForBattle();
    (async () => {
      //============================== PLAYER TURN TO FIGHT ==============================
      if (battle_turn % 2 != 0) {
        // CALCULATE DAMGE
        dms.aoe_animation(
          "player_poke_",
          "enemy_poke_",
          player_atk,
          enemy_poke
        );
        await delay(1);
        let results = aoeBattle(player_atk, enemy_poke);
        for (const result of results) {
          dms.update_hp(
            "enemy_poke_",
            result.id,
            result.hp,
            result_defender_stat
          );
          if (result.hp == 0) {
            $("#enemy_poke_" + result.id).remove();
            let index = enemy_poke.findIndex((obj) => obj.id === result.id);
            random_enemy_fight = random_enemy_fight.filter(
              (num) => num !== index
            );
            enemy_survival_list = enemy_survival_list.filter(
              (num) => num !== index
            );
          }
        }

        if (random_enemy_fight.length == 0 || !random_enemy_fight) {
          random_enemy_fight = enemy_survival_list;
        }
        if (battle_status(player_survival_list, enemy_survival_list) == "YOU WIN") {
          dms.change_round_animation("YOU WIN");
          $("#normal_atk").addClass("hidden");
          for (const poke of result_attacker_stat) {
            poke.hp = Math.round(poke.hp * 1.1);
            poke.atk = Math.round(poke.atk * 1.1);
            poke.def = Math.round(poke.def * 1.1);
            poke.level = poke.level + 1;
          }
          await delay(1);
          $.post(
            "pokemon_lab_action.php",
            {
              newData: "update_battle_team_level",
              battle_team1: JSON.stringify(result_attacker_stat),
            },
            function (response) {}
          );
          return;
        } else {
          battle_turn += 1;
        }
        $("#round_number").text(battle_turn);
      }
      // ADD CHANGE TURN ANIMATION HERE
      dms.change_round_animation("ROUND - " + battle_turn);
      await delay(1);
      dms.change_round_animation("ROUND - " + battle_turn);
      //------------------------------- ENEMY TURN TO FIGHT -------------------------------
      if (battle_turn % 2 == 0) {
        let random_enemy = dms.get_random_enemy_fight(random_enemy_fight);
        random_enemy_fight = random_enemy_fight.filter(
          (num) => num !== random_enemy
        );
        let random_player = dms.get_random_enemy_fight(player_survival_list);
        if (random_player >= 0 && enemy_survival_list.length > 0) {
          $("#enemy_poke_" + enemy_poke[random_enemy].id).addClass(
            "drop-shadow-[0px_5px_10px_rgba(242,25,0,0.8)]"
          );
          $("#player_poke_" + player_poke[random_player].id).addClass(
            "drop-shadow-[0px_5px_10px_rgba(45,242,0,0.8)]"
          );
          let player_pok_def = player_poke[random_player];

          dms.atk_animation(
            "enemy_poke_",
            "player_poke_",
            enemy_poke[random_enemy],
            player_pok_def
          );
          let result = battle(enemy_poke[random_enemy], player_pok_def);
          dms.update_hp(
            "player_poke_",
            player_pok_def.id,
            player_pok_def.hp,
            result_attacker_stat
          );
          if (result.hp == 0) {
            $("#player_poke_" + player_pok_def.id).remove();
            player_survival_list = player_survival_list.filter(
              (num) => num !== random_player
            );
          }
        }
        await delay(1);
        if (
          battle_status(player_survival_list, enemy_survival_list) == "YOU LOSE"
        ) {
          $("#normal_atk").addClass("hidden");
          battle_turn = 1;
          return;
        } else {
          battle_turn += 1;
          resetSelectPokeForBattle();
          $("#normal_atk").removeClass("hidden");
        }
      }
      $("#round_number").text(battle_turn);
      dms.change_round_animation("ROUND - " + battle_turn);
      await delay(1);
      dms.change_round_animation("ROUND - " + battle_turn);
    })();
  });
  $("#normal_atk").click(() => {
    $("#normal_atk").addClass("hidden");
    resetSelectPokeForBattle();
    (async () => {
      //============================== PLAYER TURN TO FIGHT ==============================
      if (battle_turn % 2 != 0) {
        // CALCULATE DAMGE
        dms.atk_animation(
          "player_poke_",
          "enemy_poke_",
          player_atk,
          player_def
        );
        let result = battle(player_atk, player_def);
        dms.update_hp(
          "enemy_poke_",
          result.id,
          result.hp,
          result_defender_stat
        );
        if (result.hp == 0) {
          $("#enemy_poke_" + result.id).remove();
          let index = enemy_poke.findIndex((obj) => obj.id === result.id);
          random_enemy_fight = random_enemy_fight.filter(
            (num) => num !== index
          );
          enemy_survival_list = enemy_survival_list.filter(
            (num) => num !== index
          );
        }
        if (random_enemy_fight.length == 0 || !random_enemy_fight) {
          random_enemy_fight = enemy_survival_list;
        }

        if (
          battle_status(player_survival_list, enemy_survival_list) == "YOU WIN"
        ) {
          dms.change_round_animation("YOU WIN");
          $("#normal_atk").addClass("hidden");
          for (const poke of result_attacker_stat) {
            poke.hp = Math.round(poke.hp * 1.1);
            poke.atk = Math.round(poke.atk * 1.1);
            poke.def = Math.round(poke.def * 1.1);
            poke.level +=  1;
          }
          await delay(1);
          $.post(
            "pokemon_lab_action.php",
            {
              newData: "update_battle_team_level",
              battle_team1: JSON.stringify(result_attacker_stat),
            },
            function (response) {}
          );
          return;
        } else {
          battle_turn += 1;
        }
        $("#round_number").text(battle_turn);
      }
      // ADD CHANGE TURN ANIMATION HERE
      dms.change_round_animation("ROUND - " + battle_turn);
      await delay(1);
      dms.change_round_animation("ROUND - " + battle_turn);
      //------------------------------- ENEMY TURN TO FIGHT -------------------------------
      if (battle_turn % 2 == 0) {
        let random_enemy = dms.get_random_enemy_fight(random_enemy_fight);
        random_enemy_fight = random_enemy_fight.filter(
          (num) => num !== random_enemy
        );
        let random_player = dms.get_random_enemy_fight(player_survival_list);
        if (random_player >= 0 && enemy_survival_list.length > 0) {
          $("#enemy_poke_" + enemy_poke[random_enemy].id).addClass(
            "drop-shadow-[0px_5px_10px_rgba(242,25,0,0.8)]"
          );
          $("#player_poke_" + player_poke[random_player].id).addClass(
            "drop-shadow-[0px_5px_10px_rgba(45,242,0,0.8)]"
          );
          let player_pok_def = player_poke[random_player];

          dms.atk_animation(
            "enemy_poke_",
            "player_poke_",
            enemy_poke[random_enemy],
            player_pok_def
          );
          let result = battle(enemy_poke[random_enemy], player_pok_def);
          dms.update_hp(
            "player_poke_",
            player_pok_def.id,
            player_pok_def.hp,
            result_attacker_stat
          );
          if (result.hp == 0) {
            $("#player_poke_" + player_pok_def.id).remove();
            player_survival_list = player_survival_list.filter(
              (num) => num !== random_player
            );
          }
        }
        await delay(1);
        if (
          battle_status(player_survival_list, enemy_survival_list) == "YOU LOSE"
        ) {
          $("#normal_atk").addClass("hidden");
          battle_turn = 1;
        } else {
          battle_turn += 1;
          resetSelectPokeForBattle();
          $("#normal_atk").removeClass("hidden");
        }
      }
      $("#round_number").text(battle_turn);
      dms.change_round_animation("ROUND - " + battle_turn);
      await delay(1);
      dms.change_round_animation("ROUND - " + battle_turn);
    })();
  });

  function processPlayerData(user_data) {
    function processDuegonMap(duegon_data) {
      var screenWidth = $(window).width() - 100;
      var screenHeight = $(window).height() - 300;
      var eachWidth = Math.floor(screenWidth / 10);
      let y = 50;
      let x = 50;
      //   PLAYER SECTION
      let players_coordination = dms.team_coordinate("player");
      // ENEMY SECTION
      let enemy_coordination = dms.team_coordinate("enemy");

      duegon_data.map((value, index) => {
        var map_enemy = dms.random_enemy_on_map(x, y, value, index);
        y = Math.floor(Math.random() * (screenHeight + 1)) + 50;
        x += eachWidth;
        $("#duegon_map").append(map_enemy);
        // --------------------- CLICK EACH STATE IN MAP ---------------------
        $("#map" + index).click(() => {
          if (battle_start == false) {
            result_defender_stat = JSON.parse(
              JSON.stringify(value.state_pokemons)
            );
            enemy_poke = JSON.parse(JSON.stringify(value.state_pokemons));
            player_poke = JSON.parse(JSON.stringify(user_data));
            result_attacker_stat = JSON.parse(JSON.stringify(user_data));
          }
          $("#player").empty();
          // $("#enemy").empty();
          $('[id^="enemy_poke_"]').remove();
          $("#battle_field").removeClass("hidden");

          players_coordination.map((coordination, index) => {
            let id = player_poke[index].id;
            dms.add_player_enemy_to_battle_field(
              "player",
              player_poke[index],
              coordination.bottom,
              coordination.left
            );
            // PLAYER  //
            $("#player_poke_" + id).click(() => {
              let poke = player_poke[index];
              if (battle_start) {
                if (battle_turn % 2 != 0) {
                  player_atk = poke;
                  $('[id^="player_poke_"]').removeClass(
                    "drop-shadow-[0px_5px_10px_rgba(45,242,0,0.8)]"
                  );
                  $("#player_poke_" + id).addClass(
                    "drop-shadow-[0px_5px_10px_rgba(45,242,0,0.8)]"
                  );
                }
              }
            });
          });
          enemy_coordination.map((coordination, index) => {
            let id = enemy_poke[index].id;
            dms.add_player_enemy_to_battle_field(
              "enemy",
              enemy_poke[index],
              coordination.top,
              coordination.right
            );
            // ENEMY ACTION HERE START HERE //
            $("#enemy_poke_" + id).click(() => {
              if (battle_start) {
                if (battle_turn % 2 != 0) {
                  let poke = enemy_poke[index];
                  player_def = poke;
                  $('[id^="enemy_poke_"]').removeClass(
                    "drop-shadow-[0px_5px_10px_rgba(242,25,0,0.8)]"
                  );
                  $("#enemy_poke_" + id).addClass(
                    "drop-shadow-[0px_5px_10px_rgba(242,25,0,0.8)]"
                  );
                }
              }
            });
          });
        });
      });
      $("#start_battle").click(() => {
        battle_start = true;
        result_attacker_stat = user_data;
        $("#start_battle").addClass("hidden");
      });
      $("#close_battle").click(() => {
        $("#battle_field").addClass("hidden");
        $("#start_battle").removeClass("hidden");
        $("#normal_atk").removeClass("hidden");
        battle_start = false;
        battle_turn = 1;
        player_survival_list = [0, 1, 2, 3, 4];
        random_enemy_fight = [0, 1, 2, 3, 4];
        enemy_survival_list = [0, 1, 2, 3, 4];
        player_poke = JSON.parse(JSON.stringify(user_data));
        result_attacker_stat = JSON.parse(JSON.stringify(user_data));
        dms.change_round_animation("");
        $("#round_number").text(battle_turn);
      });
    }
    dms.fetch_duegon_map(processDuegonMap);
  }
  dms.fetch_user_dex(processPlayerData);
});
