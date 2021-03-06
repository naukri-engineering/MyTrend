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
	$smarty->assign("username",$authObj->getUsername());
	$AUTH = 1;
	$ROLE = $authObj->getUsername();

	if($_POST['submit'] == 'serverGroup') {
	    $_SESSION['serverGroup'] = $_POST['serverGroup'];
	}
	$mtObj	    = new MyTrend(new DBConnection());
	$serverGroups= $mtObj->getServerGroup($ROLE);
	$smarty->assign('serverGroups',$serverGroups);
	if(!$_SESSION['serverGroup']) {
	    $smarty->assign('serverGroup','');
	    $serverGroup = '';
	    define('SERVER_GROUP',implode("|X|",$serverGroups));
	}
	else {
	    $smarty->assign('serverGroup',$_SESSION['serverGroup']);
	    $serverGroup = $_SESSION['serverGroup'];
	    define('SERVER_GROUP',$serverGroup);
	}
}
else {	
	//Failure
	$smarty->assign("AUTH",0);
	$AUTH = 0;
	$ROLE = '';
	$smarty->assign('otherUsers',$authObj->getOtherUsers());
}
?>
