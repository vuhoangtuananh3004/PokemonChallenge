<?php
session_start();
require_once('database/Create_database.php');
$create_pokemon_data = new Create_database;

$error = '';

if (isset($_SESSION['user_data'])) {
    header('location:home.php');
}

if (isset($_POST['login'])) {
    require_once('database/Models/User.php');

    $user_obj = new User;
    $user_obj->setUserEmail($_POST['user_email']);
    $user_obj->setUserPassword($_POST['user_password']);

    $user_data = $user_obj->get_user_data_by_email();
    if ($user_data && $user_data['user_password'] == $_POST['user_password']) {
        $user_obj->setUserId($user_data['user_id']);
        $user_obj->setUserLoginStatus('Login');
        if ($user_obj->update_user_login_status()) {
            $_SESSION['user_data'] = [
                'id'    =>  $user_data['user_id'],
                'name'  =>  $user_data['user_name'],
            ];
            header('location:home.php');
        }
    }else {
        $error = "Your email or password is incorrect.";
    }
}
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
    <title>POKEMON BATTLE</title>
</head>

<body>
    <div class="flex h-screen w-screen justify-center items-center bg-cover bg-no-repeat bg-center" style="background-image: url(images/wallpaper/login.jpg)">
        <audio loop style="color: white;" src="./database/file/audio_files/opening.mp3" id="opening-audio"></audio>
		<div class="flex flex-col h-[500px] w-[500px] bg-black/70 rounded-[48px] border-2 border-black/20 drop-shadow-2xl text-white">
            <div class="flex flex-col text-center h-[80px] w-full font-bold text-2xl rounded-tr-[48px] rounded-tl-[48px] justify-center items-center bg-white/80 tracking-widest drop-shadow-2xl text-black">
                <span>LOGIN</span> <span class="text-sm text-red-400"><?php echo $error; ?></span>
            </div>
            <div class="flex flex-col h-[420px] w-full justify-center p-5">
                <form method="post" id="register_form">
                    <div class="flex flex-col space-y-2 mb-3">
                        <label>Enter Your Email</label>
                        <input class="rounded-xl text-black h-7 px-2" type="text" name="user_email" id="user_email" class="form-control" data-parsley-type="email" required />
                    </div>

                    <div class="flex flex-col space-y-2 mb-3">
                        <label>Enter Your Password</label>
                        <input class="rounded-xl text-black h-7 px-2" type="password" name="user_password" id="user_password" class="form-control" data-parsley-minlength="6" data-parsley-maxlength="12" data-parsley-pattern="^[a-zA-Z]+$" required />
                    </div>
                    <div class="flex flex-col items-end space-y-2 mb-3">
                        <a class=" text-red-400 hover:text-blue-300 italic underline-offset-4 underline" href="register.php">Don't have an account?</a>
                    </div>

                    <div class="text-center mt-10">
                        <input class="rounded-xl px-5 py-1 text-xl bg-green-400/50 hover:bg-green-400/70 cursor-pointer" type="submit" name="login" class="btn btn-success" value="Login" />
                    </div>
                </form>

            </div>
        </div>
    </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <script type="text/javascript">
        $('#register_form').parsley();
    </script>
	<script>
		document.getElementById('user_email').addEventListener('click', (event) => {
            setTimeout(() => {
				let audio = document.getElementById("opening-audio");
				audio.volume = .5;
                audio.play();
            }, 1000);
        })
     
     </script>
</body>

</html>