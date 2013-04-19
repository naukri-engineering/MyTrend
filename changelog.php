<?php
/*
 * MyTrend : Change Log
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
$objMySQL   	= new MySQL();
$objMyTrend 	= new MyTrend($objMySQL);
//Fetch an array of MySQL Instances Configured for MyTrend
$instanceArr 	= $objMyTrend->getMyInstance();

$data = array();
$servers = array();
if($_POST['instance']) {
    $objChangeLog   = new ChangeLog($objMySQL);
    $mysql_id	    = $_POST['instance'];
    //Add
    if($f==1) {
	$date	= $_POST['date1'];
	$log	= $_POST['log'];
	$objChangeLog->add($mysql_id,$date,$log); //Add the change log details to Db
	$smarty->assign("success","Added succsessfully !");
    }
    //View
    else if($f==2) {
	$date1	= $_POST['date1'];
	$date2	= $_POST['date2'];		
	$data = $objChangeLog->get($mysql_id,$date1,$date2);
	foreach($data as $key=>$val) {	
	    $server = $objMyTrend->getMyInstance($val['mysql_id']);
	    $data[$key]['mysql_id'] = $server[0]['host']." (".$server[0]['port'].") - ".$server[0]['name'];
	    $data[$key]['log'] = nl2br($data[$key]['log']);
	}
	if(!count($data)) {
	    $smarty->assign("error","No data found !");
	}
    }
    $smarty->assign('data',$data);
    $smarty->assign('date1',$date1);
    $smarty->assign('date2',$date2);
    $smarty->assign('mysql_id',$mysql_id);
}
$smarty->assign("currentDD",date("d",strtotime('-1 day')));
$smarty->assign("currentMM",date("m",strtotime('-1 day')));
$smarty->assign("currentYY",date("Y",strtotime('-1 day')));
$smarty->assign('f',$f);
$smarty->assign('instanceArr',$instanceArr);
$smarty->assign('PageSelected',9);
$smarty->display('changelog.html');
?>
