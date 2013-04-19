<?php
require_once "include.php";
//Check for Authentication.
if(!$AUTH) {
    $smarty->display('index.html');
    die;
}

//Check the flag. Set it to '1' (Default)
$f = $_GET['f'];
if(!$f) {
    $f = 1;
}

$mysql_id	= $_GET['instance'];
$objMySQL   	= new MySQL();
$objMyTrend 	= new MyTrend($objMySQL);
//Fetch an array of MySQL Instances Configured for MyTrend
$instanceArr 	= $objMyTrend->getMyInstance();
if($mysql_id>0) {

    $dbSettings 	= $objMyTrend->getDatabaseSettings($mysql_id);
    $objStatusVars 	= new MyStatusVars($objMySQL,$dbSettings);

    //Get the list of global variables and their values
    $allVariables 	= $objStatusVars->getGlobalVariables();

    //Get global status
    $allStatus		= $objStatusVars->getGlobalStatus();

    $objMonitors	= new Monitors($objMySQL,$dbSettings);
    $data		= $objMonitors->getMonitorsData($f,$allVariables,$allStatus);

    $smarty->assign('data',$data);
    $smarty->assign('mysql_id',$mysql_id);
}
$smarty->assign('instanceArr',$instanceArr);
$smarty->assign('PageSelected',4);
$smarty->assign('f',$f);
$smarty->display('monitors.html');
?>
