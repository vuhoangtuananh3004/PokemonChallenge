<?php 
session_start();

if(isset($_POST['action']) && $_POST['action'] == 'Logout')
{
	require('database/Models/User.php');

	$user_object = new User;

	$user_object->setUserId($_POST['user_id']);

	$user_object->setUserLoginStatus('Logout');

	if($user_object->update_user_login_status())
	{
		unset($_SESSION['user_data']);

		session_destroy();

		echo json_encode(['status'=>1]);
	}
}
?>