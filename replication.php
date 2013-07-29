<?php
/*
 * MySQL Replication
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
$objRepl 	= new Replication($objDBConnection);
$hostArr	= array();
$slaveArr	= array();
$colors		= $objRepl->getColorCodes();
$colorId	= 0;
foreach($instanceArr as $instance) {
	$mysql_id 	= $instance['mysql_id'];
	$server_name	= $instance['name'];
	$dbSettings 	= $objMyTrend->getDatabaseSettings($mysql_id);
	//Get Server Id
	$master_id	= $objRepl->getServerId($dbSettings);
	//Get Slave Status.
	//$slaveStatus	= $objRepl->getSlaveStatus($dbSettings);	
	$slaveStatus  = 'Yes';
	$hostArr[$master_id] = array('server_id'=>$master_id,'name'=>$server_name." - ".$instance['port'],'slaveStatus'=>$slaveStatus);
	//Get List of Slaves.
	$slaves		= $objRepl->getSlaveHosts($dbSettings);
	foreach($slaves as $slave) {
		$Server_id	= $slave['Server_id'];
		$Port		= $slave['Port'];
		$name		= $hostArr[$Server_id]['name'];
		if(!$name)
			$name = $Port;
		$hostArr[$Server_id] = array('server_id'=>$Server_id,'name'=>$name,'slaveStatus'=>$slaveStatus);
		$slaveArr[]	= array('master_id'=>$master_id,'slave_id'=>$Server_id,'color'=>$colors[$colorId]);
	}
	$colorId++;
	//Close MySQL Connection.
	$objDBConnection->close();
}
$hostArr = array_values($hostArr);
$smarty->assign('hostArr',$hostArr);
$smarty->assign('slaveArr',$slaveArr);
$smarty->assign('PAGE','replication');
$smarty->assign('PageSelected',5);
$smarty->display('replication.html');
?>
