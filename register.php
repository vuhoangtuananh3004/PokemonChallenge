<?php

require 'vendor/autoload.php';
use Ramsey\Uuid\Uuid;
$uuid = Uuid::uuid4()->toString();


$error = '';
$success = '';
$marylandTimeZone = new DateTimeZone('America/New_York');
$userCreatedOn = new DateTime('now', $marylandTimeZone);
$userCreatedOnString = $userCreatedOn->format('Y-m-d H:i:s');



if (isset($_POST["register"])) {
    session_start();
    if (isset($_SESSION['user_data'])) {
        header('location:home.php');
    }
    require_once('database/Models/User.php');
    $user_obj = new User;
    
    $user_obj->setUserId($uuid);   
    while ($user_obj->get_user_data_by_id() == true){
        $uuid = Uuid::uuid4()->toString();
        $user_obj->setUserId($uuid);
    }
    $user_obj->setUserName($_POST['user_name']);

    $user_obj->setUserEmail($_POST['user_email']);

    $user_obj->setUserPassword($_POST['user_password']);

    $user_obj->setUserStatus('Enable');
    $user_obj->setUserLoginStatus('Logout');
    $user_obj->setUserCreatedOn($userCreatedOnString);


    $user_data = $user_obj->get_user_data_by_email();
    if ($user_data) {
        $error = "This Email is linked to another account!!!!";
        $success ='';
    } else {
        $user_obj->save_data();
        $success = "Account create successfully!!!!";
        $error ='';
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
    <title>Register</title>
</head>

<body>
    <div class="flex h-screen w-screen justify-center items-center bg-cover bg-no-repeat bg-center" style="background-image: url(images/wallpaper/register.jpg)">
        <div class="flex flex-col h-[500px] w-[500px] bg-black/70 rounded-[48px] border-2 border-black/20 drop-shadow-2xl text-white">
            <div class="flex flex-col h-[80px] w-full font-bold text-2xl rounded-tr-[48px] rounded-tl-[48px] justify-center items-center bg-white/80 tracking-widest drop-shadow-2xl text-black">
                <span>REGISTER</span>
                <?php echo "<div class='text-sm text-red-500 tracking-wide'>$error</div>" ?>
                <?php echo "<div class='text-sm text-green-500 tracking-wide'>$success</div>" ?>
            </div>
            <div class="flex flex-col h-[420px] w-full justify-center p-5">
                <form method="post" id="register_form">
                    <div class="flex flex-col space-y-2 mb-3">
                        <label>Enter Your Name</label>
                        <input class="rounded-xl text-black px-2" type="text" name="user_name" id="user_name" class="form-control" data-parsley-pattern="/^[a-zA-Z\s]+$/" required />
                    </div>

                    <div class="flex flex-col space-y-2 mb-3">
                        <label>Enter Your Email</label>
                        <input class="rounded-xl text-black px-2" type="text" name="user_email" id="user_email" class="form-control" data-parsley-type="email" required />
                    </div>

                    <div class="flex flex-col space-y-2 mb-3">
                        <label>Enter Your Password</label>
                        <input class="rounded-xl text-black px-2" type="password" name="user_password" id="user_password" class="form-control" data-parsley-minlength="6" data-parsley-maxlength="12" data-parsley-pattern="^[a-zA-Z]+$" required />
                    </div>
                    <div class="flex flex-col items-end space-y-2 mb-3">
                        <a class=" text-red-400 hover:text-blue-300 italic underline-offset-4 underline" href="index.php">Already have an account? Login</a>
                    </div>

                    <div class="form-group text-center mt-10">
                        <input class="rounded-xl px-5 py-1 text-xl bg-green-400/50 hover:bg-green-400/70 cursor-pointer" type="submit" name="register" class="btn btn-success" value="Register" />
                    </div>
                </form>

            </div>
        </div>
    </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <script type="text/javascript">
        $('#register_form').parsley();
    </script>
</body>

</html>