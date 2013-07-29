<?php
/*
 * Author : Amardeep Vishwakarma
 */
//Include the Smarty File (Templating Engine)
require_once "html/smarty/Smarty.class.php";
$smarty = new Smarty;

//Autoload Classes
function __autoload($class_name) {
	require 'classes/'.$class_name . '.php';
}

//Checks for the authentication.
session_start();
$authObj	= new Authenticate(new DBConnection());
if($authObj->authenticate()) {	
	//Success
	$smarty->assign("AUTH",1);
	$smarty->assign("role",$authObj->getRole());
	$AUTH = 1;
	$ROLE = $authObj->getRole();
}
else {	
	//Failure
	$smarty->assign("AUTH",0);
	$AUTH = 0;
	$ROLE = '';
}
?>
