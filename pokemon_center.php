<?php
?>
<div class="flex flex-col space-y-3">
    <div class="grid grid-cols-4  h-[400px] w-full overflow-auto text-center" id="pokemon_center_display"></div>
    <div class="w-full h-2 bg-black rounded-full"></div>
    <span class="text-center text-yellow-300 font-bold tracking-wider font-bold text-xl">BATTLE TEAM</span>
    <div class="flex flex-row h-[180px] w-full justify-around items-center" id="battle_team_display">
    </div>
    <span class="text-center mt-5 px-5 py-2 bg-green-400/80 rounded-full text-xl font-bold cursor-pointer hover:bg-green-400" id="saveBtn">SAVE</span>
</div>
<script>
    $(document).ready(function() {
        let battle_team = [];
        let pokedex_team = [];
        $.ajax({
            url: 'pokemon_lab_action.php',
            type: 'GET',
            data: {
                data: "get_user_pokedex",
            },
            success: function(response) {
                var data = JSON.parse(response);
                battle_team = data.filter(item => item.battle_team === "TRUE");
                pokedex_team = data.filter(item => item.battle_team === "FALSE");

                const resetDisplay = () => {
                    $('#pokemon_center_display').empty();
                    $('#battle_team_display').empty();
                }
                const display_pokedex_team = (team) => {
                    if (team.length != 0) {
                        team.map((value, index) => {
                            var pokemon = $(`<div id="pokedex${index}" class="fex flex-col h-[200px] w-[150px] text-center transition-opacity duration-[3000ms]" style="opacity:0;"><div class=" h-[150px] w-[150px] bg-contain bg-no-repeat bg-center cursor-pointer" style="background-image: url(${value.data});" id=''></div><div>${value.name}</div><div>Level: ${value.level}</div></div>`)
                            pokemon.css('opacity', '1');
                            $('#pokemon_center_display').append(pokemon);
                            if (battle_team.length < 5) {
                                $('#pokedex' + index).click(() => {
                                    resetDisplay();
                                    value.battle_team = "TRUE"
                                    let temp = value;
                                    pokedex_team.splice(index, 1);
                                    battle_team.push(value);
                                    display_pokedex_team(pokedex_team);
                                    display_battle_team(battle_team);
                                })
                            }
                        })
                    }
                }
                const display_battle_team = (team) => {
                    for (let i = 0; i < 5; i++) {
                        if (team.length > i) {
                            var pokemon = $(`<div id="battle${i}" class="fex flex-col h-[200px] w-[150px] text-center transition-opacity duration-[3000ms]" style="opacity:0;"><div class=" h-[150px] w-[150px] bg-contain bg-no-repeat bg-center cursor-pointer" style="background-image: url(${team[i].data});" id=''></div><div>${team[i].name}</div><div>Total: ${team[i].total}</div></div>`)
                            pokemon.css('opacity', '1');
                            $('#battle_team_display').append(pokemon);
                            $('#battle' + i).click(() => {
                                resetDisplay();
                                team[i].battle_team = "FALSE";
                                let temp = team[i];
                                battle_team.splice(i, 1);
                                pokedex_team.push(temp);
                                display_pokedex_team(pokedex_team);
                                display_battle_team(battle_team);
                            })
                        } else {
                            let empty = $(`<div class="flex h-[100px] w-[100px] border-2 border-white justify-center items-center border-dashed rounded-md">+</div>`);
                            $('#battle_team_display').append(empty);
                        }
                    }
                }
                display_pokedex_team(pokedex_team);
                display_battle_team(battle_team);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });

        $('#saveBtn').click(() => {
            let power = battle_team.reduce((accumulator, object) => {
                if (object.battle_team == "TRUE") {
                    return accumulator + object.total;
                }
                return accumulator + 0;
            }, 0);

            $('#power').text(power);
            $.post('pokemon_lab_action.php', {
                data: 'update_battle_team',
                battle_team: JSON.stringify(battle_team)
            }, function(response) {

            })
        })

    });


      
</script>