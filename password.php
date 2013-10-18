<?php
/*
 * Change Password File.
 * Author : Amardeep Vishwakarma
 */
require_once "include.php";
if(!$AUTH || $ROLE != 'superadmin') {
    $smarty->display('index.html');
    die;
}
if($_POST['submit']) {
    $sa_password        = trim($_POST['sa_password']);  	//SuperAdmin Password
    $a_password         = trim($_POST['a_password']);   	//Admin Password

    $sa_c_password      = trim($_POST['sa_c_password']);	//Confirm Password
    $a_c_password       = trim($_POST['a_c_password']); 	//Confirm Password
    /*
    //Basic Validations
    if(!$sa_password || !$a_password || strlen($sa_password)<6 || strlen($a_password)<6) {
    $error = 'Both the passwords are mandatory. 6 characters or more! Be tricky.';
    }
    elseif($sa_password != $sa_c_password) {
    $error = "Super Admin : The password you've confirmed does not match.";
    }
    elseif($a_password != $a_c_password) {
    $error = "Admin : The password you've confirmed does not match.";
    }
    else {
     */
    //Update the passwords in mytrend_users table.
    $mySQLObj = new DBConnection();

    //Encrypt both the Passwords.
    $sa_password    = md5($sa_password);	
    $a_password     = md5($a_password);

    if($_POST['submit'] == 'submitSA') {
	//Update Super Admin User Password
	$sql = "UPDATE `mytrend_users` SET `password`=:sa_password where `role`='superadmin'";
	$params = array('sa_password'=>$sa_password);
	$mySQLObj->queryPDO($sql,array(),$params);
    }
    if($_POST['submit'] == 'submitA') {
	//Update Admin User Password
	$sql = "UPDATE `mytrend_users` SET `password`=:a_password where `role`='admin'";
	$params = array('a_password'=>$a_password);
	$mySQLObj->queryPDO($sql,array(),$params);
    }
    $success = "Password has been changed successfully !";
    //}
    $smarty->assign('error',$error);
    $smarty->assign('success',$success);
}
$smarty->assign('PageSelected',8);
$smarty->display('password.html');
?>
