<div class="flex hidden h-full w-full items-center justify-center z-20" id="pokemon_lab_open">
    <div class="flex flex-col h-[700px] w-[700px] justify-end items-center bg-black/50 rounded-[48px] drop-shadow-2xl p-5 border-2 border-red-300">
        <div class="flex w-full justify-end p-2 text-2xl cursor-pointer" id="pokemon_lab_close">X</div>
        <div class="grid grid-cols-3 h-full w-full overflow-auto text-center" id="display_draw_pokemon">

        </div>
        <div class="text-yellow-300 mb-5 text-xl italic font-bold hidden" name="not_enough_gold" id="not_enough_gold">Not Enough Medal</div>
        <div class="h-[100px] w-[100px] bg-contain bg-no-repeat bg-center cursor-pointer hover:animate-bounce" style="background-image: url(images/homeImg/pokemon_roller.png);" name="draw_pokemon" id="draw_pokemon">
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#draw_pokemon').click(() => {
            $.ajax({
                url: 'pokemon_lab_action.php',
                type: 'GET',
                data: {
                    data: "user_info"
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    let user_gold = data.user_gold;
                    // let user_gold = 20000;
                    if (user_gold - 2000 >= 0) {
                        user_gold -= 2000;
                        $('#medal').text(user_gold);
                        $.ajax({
                            url: 'pokemon_lab_action.php',
                            type: 'GET',
                            data: {
                                data: "generate_pokemon", user_gold: user_gold
                            },
                            success: function(response) {
                                var timer = 200;
                                var data = JSON.parse(response);
                                console.log('====================================');
                                console.log(data[0]);
                                console.log('====================================');
                                data.map((value, index) => {
                                    var pokemon = $(`<div class="fex flex-col h-[200px] w-[150px] text-center transition-opacity duration-[3000ms]" style="opacity:0;"><div class=" h-[150px] w-[150px] bg-contain bg-no-repeat bg-center cursor-pointer" style="background-image: url(${value.data});" id=''></div><span>${value.name}</span></div>`)
                                    console.log(value);
                                    setTimeout(function() {
                                        pokemon.css('opacity', '1');
                                    }, timer);
                                    $('#display_draw_pokemon').append(pokemon);
                                    timer += 500;
                                })
                            },
                            error: function(xhr, status, error) {
                                console.error(error);
                                console.log("NOT WORK");
                            }
                        });
                    } else {
                        $("#not_enough_gold").removeClass('hidden');
                    }

                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });

        })
    });
</script>