<?php
/*
 * MyTrend Graphs.
 * Author : Amardeep Vishwakarma
 */
require_once "include.php";
//Check for Authentication.
if(!$AUTH) {
	$smarty->display('index.html');
	die;
}
$objDBConnection 	= new DBConnection();
$objMyTrend = new MyTrend($objDBConnection);
//Fetch an array of MySQL Instances Configured for MyTrend
$instanceArr = $objMyTrend->getMyInstance();
$smarty->assign('instanceArr',$instanceArr);


if($_GET['mysql_id']) {
	$mysql_id 	= $_GET['mysql_id'];
	$f			= $_GET['f'];
	$date1		= $_GET['date1'];
	$date2		= $_GET['date2'];
	if($_GET['database']) {
		$database	= $_GET['database'];
		//Fetch Databases.
		$databaseArr= $objMyTrend->getDatabase($mysql_id);
		$smarty->assign('databaseArr',$databaseArr);
	}
	if($_GET['table']) {
		$table		= $_GET['table'];
		//Fetch Tables
		$tableArr	= $objMyTrend->getTables($mysql_id,$database);
		$smarty->assign('tableArr',$tableArr);
	}
	$smarty->assign('mysql_id',$mysql_id);
	$smarty->assign('database',$database);
	$smarty->assign('table',$table);
	$smarty->assign('date1',$date1);
	$smarty->assign('date2',$date2);
	$smarty->assign('type','size');
	$smarty->assign('onload','yes');
}

$f = $_GET['f'];
if(!$f) {
	$f = 1;
}

if($f==7) {
	$v = $_GET['v'];
	$objMyStatusVars    = new MyStatusVars($objDBConnection);
	//Get list of selected Variables.
	$variables      = $objMyStatusVars->getSelectedVariables();
	$smarty->assign('variables',$variables);
	$smarty->assign("v",$v);
}
else if($f==8) {
	$objCustomizedGraphs    = new CustomizedGraphs($objDBConnection);
	$clabels		= $objCustomizedGraphs->getList();
	$smarty->assign('clabels',$clabels);
}

$smarty->assign("currentDD",date("d",strtotime('-1 day')));
$smarty->assign("currentMM",date("m",strtotime('-1 day')));
$smarty->assign("currentYY",date("Y",strtotime('-1 day')));
$smarty->assign("f",$f);
$smarty->assign('PageSelected',2);
$smarty->display('graphs.html');
?>
