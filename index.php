<?php
/*
* Author : Amardeep Vishwakarma
*/
require_once "include.php";
require_once "config/settings.php";

//If all the steps of installation are done, redirect this to index.php.
if($INSTALLED != 'YES') {
	header('Location:install.php');
	die;
}

if($_POST['submit']) {
	$role 		= $_POST['role'];
	$password	= $_POST['password'];
	//Check the Password
	if($authObj->checkPassword($role,$password)) {
		//Success
		$smarty->assign("AUTH",1);
		$smarty->assign("role",$role);
		header('Location:stats.php');
		die;
	}
	else {
		//Failure
		$smarty->assign('error','Wrong role and password combination.');
	}
}
$smarty->display('index.html');
?>
