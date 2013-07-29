<?php
/*
 * MyTrend : Server Configs.
 * Author : Amardeep Vishwakarma
 */

require_once "include.php";
//Check for Authentication. If not logged-in, go to Login page
if(!$AUTH) {
	$smarty->display('index.html');
	die;
}

//Check the flag. Set it to '1' (Default)
$f = $_REQUEST['f'];
if(!$f) {
	$f = 1;
}
$objDBConnection   	= new DBConnection();
$objMyTrend 	= new MyTrend($objDBConnection);
//Fetch an array of MySQL Instances Configured for MyTrend
$instanceArr 	= $objMyTrend->getMyInstance();

$data = array();
$servers = array();
if($_POST['instance']) {
	$instances = $_POST['instance'];
	//Server-wise compare
	if($f==1) {
		foreach($instances as $mysql_id) {
			$dbSettings = $objMyTrend->getDatabaseSettings($mysql_id);
			$objStatusVars = new MyStatusVars($objDBConnection,$dbSettings);
			//Get the list of global variables and their values
			$arr = $objStatusVars->getGlobalVariables();
			foreach($arr as $name=>$value) {
				$data[$name][$mysql_id] = $value;
			}
			$server	   = $objMyTrend->getMyInstance($mysql_id);
			$servers[] = $server[0];
		}
		unset($arr);
	}
	//Date-wise compare
	else if($f==2) {
		$mysql_id = $_POST['instance'];
		$date1		= $_POST['date1'];
		$date2		= $_POST['date2'];		
		$objStatusVars = new MyStatusVars($objDBConnection); 
		//Get the details for date 1
		$arr1 = $objStatusVars->getServerConfigVariables($mysql_id,$date1);
		foreach($arr1 as $name=>$value) {
			$data[$name][$date1] = $value;
		}
		//Get the details for date 2
		$arr2 = $objStatusVars->getServerConfigVariables($mysql_id,$date2);
		foreach($arr2 as $name=>$value) {
			$data[$name][$date2] = $value;
		}
	}
	foreach($data as $name=>$value) {
		$arr[] = array("name"=>$name,"value"=>$value);
	}
	$smarty->assign('servers',$servers);
	$smarty->assign('data',$arr);
	$smarty->assign('date1',$date1);
	$smarty->assign('date2',$date2);
	$smarty->assign('date1data',count($arr1));
	$smarty->assign('date2data',count($arr2));
	$smarty->assign('mysql_id',$mysql_id);
}
$smarty->assign("currentDD",date("d",strtotime('-1 day')));
$smarty->assign("currentMM",date("m",strtotime('-1 day')));
$smarty->assign("currentYY",date("Y",strtotime('-1 day')));

$smarty->assign('f',$f);
$smarty->assign('instanceArr',$instanceArr);
$smarty->assign('PageSelected',3);
$smarty->display('server-config.html');
?>
