<?php
/*
 * Change Password File.
 * Author : Amardeep Vishwakarma
 */
require_once "include.php";
if(!$AUTH || $ROLE == 'admin') {
		$smarty->display('index.html');
		die;
}
if($_POST['submit']) {
		$sa_password        = trim($_POST['sa_password']);  	//SuperAdmin Password
		$a_password         = trim($_POST['a_password']);   	//Admin Password

		$sa_c_password      = trim($_POST['sa_c_password']);	//Confirm Password
		$a_c_password       = trim($_POST['a_c_password']); 	//Confirm Password

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
				//Update the passwords in mytrend_users table.
				$mySQLObj = new MySQL();
			
				//Encrypt both the Passwords.
				$sa_password    = addslashes(md5($sa_password));	
				$a_password     = addslashes(md5($a_password));

				//Update Super Admin User Password
				$sql = "UPDATE `mytrend_users` SET `password`='$sa_password' where `role`='superadmin'";
				$mySQLObj->query($sql);
	
				//Update Role User Password
				$sql = "UPDATE `mytrend_users` SET `password`='$a_password' where `role`='admin'";
				$mySQLObj->query($sql);

				$success = "Password has been changed successfully !";
		}
		$smarty->assign('error',$error);
		$smarty->assign('success',$success);
}
$smarty->display('password.html');
?>
