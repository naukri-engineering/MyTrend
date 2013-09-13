<?php
/*
 * MySQL Processlist - Running query Information
 * Author : Amardeep Vishwakarma
 */
require_once "include.php";
if(!$AUTH) {
	$smarty->display('index.html');
	die;
}
$objDBConnection 	= new DBConnection();
$objMyTrend	= new MyTrend($objDBConnection);
//Fetch an array of MySQL Instance configured for MyTrend
$instanceArr = $objMyTrend->getMyInstance();
$listtype = $_GET['listtype'];
if(!$listtype) {
    $listtype = 1;
}
if($_GET['instance']) {
	$mysql_id   = $_GET['instance'];
	$dbSettings = $objMyTrend->getDatabaseSettings($mysql_id);
	$objProcessList = new Processlist($objDBConnection,$dbSettings);
	//Get the data for current running processlist
	$arrProcesslist = $objProcessList->getProcessList($listtype);
	$smarty->assign('arrProcesslist',$arrProcesslist);
	$smarty->assign('mysql_id',$mysql_id);
}
if($_GET['interval']) {
	$interval = $_GET['interval'];
}
else {
	$interval = 5;	//Default Refresh Time
}
$smarty->assign('instanceArr',$instanceArr);
$smarty->assign('listtype',$listtype);
$smarty->assign('interval',$interval);
$smarty->assign('PageSelected',6);
$smarty->display('processlist.html');
?>
