export function random_spc() {
  const randomNumber = Math.random();
  const number = Math.floor(randomNumber * 5) + 1;
  return number;
}

export function update_hp(who_turn, id, hp_after_damage, origin_poke_stats) {
  let origin_hp = origin_poke_stats.filter((item) => item.id == id)[0].hp;
  let percentageHpLoss = (hp_after_damage / origin_hp) * 100;
  let hpLeft = 100 - (100 - percentageHpLoss);
  $("#" + who_turn + id + "_hp_bar").css("width", hpLeft + "px");
  $("#" + who_turn + id + "_hp_number").text("HP: " + hp_after_damage);
}

export function get_random_enemy_fight(arr) {
  const randomIndex = Math.floor(Math.random() * arr.length);
  return arr[randomIndex];
}

export function team_coordinate(team_roll) {
  switch (team_roll) {
    case "player":
      return [
        { bottom: 50, left: 250 },
        { bottom: 150, left: 100 },
        { bottom: 300, left: 50 },
        { bottom: 215, left: 235 },
        { bottom: 135, left: 405 },
      ];
    case "enemy":
      return [
        { top: 50, right: 250 },
        { top: 150, right: 100 },
        { top: 300, right: 50 },
        { top: 215, right: 235 },
        { top: 135, right: 405 },
      ];
  }
}
export function random_enemy_on_map(x, y, value, index) {
  return $(`
    <div id="map${index}" class="absolute left-[${x}px] top-[${y}px] cursor-pointer">
        <div class="flex flex-col h-full w-full items-center justify-center space-y-2">
            <div class="h-[100px] w-[100px] bg-cover bg-no-repeat bg-center"
                style="background-image: url(${
                  value.state_pokemons[0].data
                })"></div>
            <div class="font-bold text-black bg-white/60 rounded-full px-3">State 1 - ${
              index + 1
            }</div>
            <div class="flex flex-row items-center justify-center">
                <div class="h-[30px] w-[30px] bg-contain bg-no-repeat bg-center rounded-full cursor-pointer ml-3 bg-yellow-400"
                    style="background-image: url(images/homeImg/power.svg);">
                </div>: 
                <span class="text-yellow-500 text-lg font-bold tracking-wider bg-green-600/80 px-2 rounded-full">${
                  value.state_power
                }</span>
            </div>
        </div>
    </div>`);
}

function player_html(index, bottom, left, img_url, hp) {
  return `<div id="player_poke_${index}"  class="absolute h-[120px] w-[100px] bottom-[${bottom}px] left-[${left}px] cursor-pointer">
    <div class="flex flex-col h-full w-full justify-between items-center">  
        <div class="h-[100px] w-[100px] bg-contain bg-no-repeat bg-center -scale-x-100" style="background-image: url(${img_url})"></div>
        <div class="relative h-[18px] w-[100px] rounded-full">
        <div class="absolute top-0 lef-0 h-full w-full bg-red-500 rounded-full"></div>
        <div id="player_poke_${index}_hp_bar" class="absolute top-0 lef-0 h-full w-[100px] z-10 bg-green-500 rounded-full" ></div>
        <div id="player_poke_${index}_hp_number" class="absolute -top-[3px] left-[5px] h-full w-full z-20 rounded-full text-small font-bold">HP:${hp}</div>
        </div>
  </div>
</div>`;
}

function enemy_html(index, top, right, img_url, hp) {
  return `<div id="enemy_poke_${index}"  class="absolute h-[120px] w-[100px] top-[${top}px] right-[${right}px] cursor-pointer">
    <div class="flex flex-col h-full w-full justify-between items-center">     
        <div class="h-[100px] w-[100px] bg-contain bg-no-repeat bg-center"
            style="background-image: url(${img_url})"></div>
            <div class="relative h-[18px] w-[100px] rounded-full">
                <div class="absolute top-0 lef-0 h-full w-full bg-red-500 rounded-full"></div>
                <div id="enemy_poke_${index}_hp_bar" class="absolute top-0 lef-0 h-full w-[100px] z-10 bg-green-500 rounded-full" ></div>
                <div id="enemy_poke_${index}_hp_number" class="absolute -top-[3px] left-[5px] h-full w-full z-20 rounded-full text-small font-bold">HP:${hp}</div>
            </div>
    </div>
</div>`;
}
// GET POKEMON ON BATTLE FIELD COORDINATE
export function add_player_enemy_to_battle_field(team, player_poke, y, x) {
  let id = player_poke.id;
  let hp = player_poke.hp;
  let img_url = player_poke.data;

  switch (team) {
    case "player":
      let add_player_html = player_html(id, y, x, img_url, hp);
      $("#player").append(add_player_html);
      break;
    case "enemy":
      let add_enemy_html = enemy_html(id, y, x, img_url, hp);
      $("#enemy").append(add_enemy_html);
      break;

    default:
      break;
  }
}
export function atk_animation(player_poke, enemy_poke, atacker, defender) {
  let moveAtkAnimation =
    player_poke == "player_poke_" ? "atk_moveR" : "atk_moveL";
  $("#" + player_poke + atacker.id).addClass(moveAtkAnimation);
  $("#" + enemy_poke + defender.id).addClass(
    "animate__animated animate__pulse"
  );
  setTimeout(() => {
    $("#" + player_poke + atacker.id).removeClass(moveAtkAnimation);
    $("#" + enemy_poke + defender.id).removeClass(
      "animate__animated animate__pulse"
    );
  }, 1000);
}
export function aoe_animation(player_poke, enemy_poke, atacker, defenders) {
  let moveAtkAnimation =
    player_poke == "player_poke_" ? "atk_moveR" : "atk_moveL";
  $("#" + player_poke + atacker.id).addClass(moveAtkAnimation);
  $("#skill1_play").removeClass("hidden");
  setTimeout(()=>{
    $("#skill1_play").addClass("hidden");
  

  },500)
  for (const defender of defenders) {
    $("#" + enemy_poke + defender.id).addClass(
      "animate__animated animate__pulse"
    );
    setTimeout(() => {
      $("#" + player_poke + atacker.id).removeClass(moveAtkAnimation);
      $("#" + enemy_poke + defender.id).removeClass(
        "animate__animated animate__pulse"
      );
    }, 500);
  }
}
export function change_round_animation(text) {
  $("#change_round").hasClass("hidden")
    ? $("#change_round").removeClass("hidden")
    : $("#change_round").addClass("hidden");
  $("#change_round_num").text(text);
}
export function fetch_user_dex(callback) {
  $.ajax({
    url: "pokemon_lab_action.php",
    type: "GET",
    data: {
      data: "get_user_pokedex",
    },
    success: function (response) {
      var data = JSON.parse(response);
      data = data.filter((item) => item.battle_team == "TRUE");
      callback(data);
    },
    error: function (xhr, status, error) {
      console.error(error);
      callback(null, error);
    },
  });
}
export function fetch_duegon_map(callback) {
  $.ajax({
    url: "pokemon_lab_action.php",
    type: "GET",
    data: {
      data: "get_duegon_map",
    },
    success: function (response) {
      var data = JSON.parse(response);
      callback(data);
    },
    error: function (xhr, status, error) {
      console.error(error);
      callback(null, error);
    },
  });
}
