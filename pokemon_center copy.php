<?php ?>
<div class="flex hidden h-full w-full items-center justify-center z-30" id="pokemon_center_open">
    <div class="flex flex-col h-[700px] w-[800px] justify-end items-center bg-black/50 rounded-[48px] drop-shadow-2xl p-5 border-2 border-red-300">
        <div class="flex w-full justify-end p-2 text-2xl cursor-pointer" id="pokemon_center_close">X</div>
        <div class="grid grid-cols-4 h-full w-full overflow-auto text-center" id="pokemon_center_display">
        </div>
        <div class="flex h-[200px] w-full justify-center items-center">
            <div class="flex h-[200px] w-[200px] bg-white"></div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        console.log("yes");

    });
</script>