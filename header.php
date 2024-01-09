<div class="flex flex-row justify-end items-center p-5">
    <div class="flex flex-row h-[50px] w-[250px] justify-center items-center bg-gradient-to-l from-violet-500/20 to-fuchsia-500 rounded-tl-full rounded-bl-full">
        <div class="h-[40px] w-[40px] bg-contain bg-no-repeat bg-center rounded-full cursor-pointer" style="background-image: url(images/homeImg/medal.svg);">
        </div>
        <span class="text-xl font-bold text-yellow-300" id="medal"></span>
        <div class="h-[40px] w-[40px] bg-contain bg-no-repeat bg-center rounded-full cursor-pointer ml-3" style="background-image: url(images/homeImg/power.svg);">
        </div>
        <span class="text-xl font-bold text-yellow-300" id="power"></span>
    </div>
    <div class="h-[75px] w-[75px] bg-contain bg-no-repeat bg-center" style="background-image: url(images/homeImg/4.svg);">
    </div>
    <div class="flex flex-row h-[50px] w-[200px] justify-around items-center bg-gradient-to-r from-violet-500/20 to-fuchsia-500 rounded-tr-full rounded-br-full">
        <div class="flex flex-col w-3/4">
            <div class="flex ml-5" style="font-family: 'Bangers', cursive;">Lv 100</div>
            <div class="flex flex-col w-full justiy-center items-center font-serif italic font-bold text-xl">
                <?php echo $user_data['name'] ?></div>
        </div>
        <div class="h-[25px] w-[25px] bg-contain bg-no-repeat bg-center rounded-full cursor-pointer" style="background-image: url(images/homeImg/setting.gif);" name="setting" id="setting">
        </div>
    </div>
</div>

<div class="absolute hidden h-screen w-screen z-20" id="setting_board">
    <div class="flex h-full w-full justify-center items-center text-white">
        <div class="flex flex-col h-[400px] w-[600px] justify-center items-center bg-amber-900/80 rounded-[48px] drop-shadow-2xl space-y-5 p-5">
            <span class="text-center text-[24px] py-2 w-[200px] bg-green-600/70 hover:bg-green-500 rounded-[24px] font-bold cursor-pointer" id="close_setting">CLOSE</span>
            <span class="text-center text-[24px] py-2 w-[200px] bg-red-600/70 hover:bg-red-500 rounded-[24px] font-bold cursor-pointer" id="log_out">LOGOUT</span>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let user_info = <?php echo $user_info; ?>;
        let user_dex = <?php echo $user_dex; ?>;
        let power = 0;
        if (user_dex) {
            power = user_dex.reduce((accumulator, object) => {
                if (object.battle_team == "TRUE") {
                    return accumulator + object.total;
                }
                return accumulator + 0;
            }, 0);
        }


        $('#medal').text(user_info.user_gold);
        $('#power').text(power);
        $("#setting").click(() => {
            $("#setting_board").removeClass('hidden');
            console.log("work");
        });
        $("#close_setting").click(() => {
            $("#setting_board").addClass('hidden');
        });

        $("#log_out").click(() => {
            var user_id = "<?php echo $user_id; ?>";

            $.ajax({
                url: 'logout_action.php',
                type: 'POST',
                data: {
                    user_id: user_id,
                    action: "Logout"
                },
                success: function(response) {
                    console.log(response);
                    var status = JSON.parse(response);
                    (status.status == 1) ? location = 'index.php': '';
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>