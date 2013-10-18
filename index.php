<?php
/*
 * Author : Amardeep Vishwakarma
 */
require_once "include.php";
require_once "config/settings.php";

//If all the steps of installation are done, redirect this to index.php.
//if($INSTALLED != 'YES') {
//	header('Location:install.php');
//	die;
//}
if($_POST['submit']) {
	$username 	= $_POST['username'];
	$password	= $_POST['password'];
	$otherUsername	= $_POST['otherUsername'];
	if($username=='other') {
		$username = $otherUsername;
	}
	//Check the Password
	if($authObj->checkPassword($username,$password)) {
		//Success
		$smarty->assign("AUTH",1);
		$smarty->assign("username",$username);
		header('Location:stats.php');
		die;
	}
	else {
		//Failure
		$smarty->assign('error','Wrong username and password combination.');
	}
}
$smarty->display('index.html');
?>
