<?php
/*
 * MyTrend Stats.
 * Author : Amardeep Vishwakarma
 */
require_once "include.php";
//If not logged-in, go to Login page
if(!$AUTH) {
    $smarty->display('index.html');
    die;
}
$objMySQL 	= new MySQL();
$objMyTrend	= new MyTrend($objMySQL);
//Fetch an array of MySQL Instance configured for MyTrend
$instanceArr = $objMyTrend->getMyInstance();
$smarty->assign('instanceArr',$instanceArr);

//Check the flag. Set it to '1' (Default)
$f = $_GET['f'];
if(!$f)
    $f = 1;

    if($f==7) {
	$objMyStatusVars	= new MyStatusVars($objMySQL);
	//Get list of selected MySQL Variables.
	$variables		= $objMyStatusVars->getSelectedVariables();
	$smarty->assign('variables',$variables);
    }
$smarty->assign("currentDD",date("d",strtotime('-1 day')));
$smarty->assign("currentMM",date("m",strtotime('-1 day')));
$smarty->assign("currentYY",date("Y",strtotime('-1 day')));
$smarty->assign("f",$f);
$smarty->assign('PageSelected',1);
$smarty->display('stats.html');
?>
