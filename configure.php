<?php
/*
 * MyTrend Configuration
 * Author : Amardeep Vishwakarma
 */
require_once "include.php";

//Check for the authentication..
if(!$AUTH || $ROLE == 'admin') {
	$smarty->display('index.html');
	die;
}

$objDBConnection 		= new DBConnection();
$objMyTrend	= new MyTrend($objDBConnection);
$instanceArr    = array();
$instanceArr 	= $objMyTrend->getMyInstance();
$f = $_REQUEST['f'];
if(!$f) {
	$f = 1;
}
if($f==1) { //Configure MySQL Instances
	$name       = $_POST['name'];
	$host       = $_POST['host'];
	$port       = $_POST['port'];
	$username   = $_POST['username'];
	$password   = $_POST['password'];
	if($_POST['submit'] == 'add') {
		if(mysql_connect("$host:$port",$username,$password)) {
			//Add new MySQL Instance
			$status = $objMyTrend->addMyInstance($name,$host,$port,$username,$password);
			if($status) {
				$success = "$host ($port) Added Successfully !";
				$host = $port = $username = $password = '';
			}
			else {
				$error = "Entry with the specified host/port already exists.";
			}
		}
		else {
			$error  = "$host ($port) : Can't connect to MySQL - Unable to connect to the specified hosts";
		}

	}
	else if($_POST['submit'] == 'edit') {
		$mysql_id = $_POST['mysql_id'];
		if(mysql_connect("$host:$port",$username,$password)) {
			$objMyTrend->updateMyInstance($mysql_id,$name,$host,$port,$username,$password);
			$success = "$host ($port) Updated Successfully !";
			$host = $port = $username = $password = '';
		}
		else {
			$error  = "$host ($port) : Can't connect to MySQL - Unable to connect to the specified hosts";
		}
		$smarty->assign('mysql_id',$mysql_id);
	}
	$instanceArr 	= $objMyTrend->getMyInstance();
	foreach($instanceArr as $key=>$instance) {
		$host_tmp		= $instance['host'];
		$port_tmp		= $instance['port'];
		$username_tmp	= $instance['username'];
		$password_tmp	= $instance['password'];
		$conn = mysql_connect("$host_tmp:$port_tmp",$username_tmp,$password_tmp);
		if($conn) {
			$instanceArr[$key]['status'] = 1;
		}
		else {
			$instanceArr[$key]['status'] = 0;
		}
	}
	$smarty->assign('name',$name);
	$smarty->assign('host',$host);
	$smarty->assign('port',$port);
	$smarty->assign('username',$username);
	$smarty->assign('password',$password);
}
else if($f==2) { //Ignore Database
	if($_POST['submit'] == 'show' || $_POST['submit'] == 'add') {
		$mysql_id = $_POST['mysql_id'];
		$databases = $objMyTrend->getDatabase($mysql_id);
		if($_POST['submit'] == 'add') {
			$objMyTrend->updateIgnoreDatabase($mysql_id,$_POST['database']);
			$success = "Selected Databases has been added to the Ignore List";
		}
		$IgnoredDb = $objMyTrend->getIgnoreDatabase($mysql_id);
		foreach($databases as $key=>$db) {
			$selected = '';
			if(in_array($db,$IgnoredDb))
				$selected = 'selected';
			$databases[$key] = array('db'=>$db,'selected'=>$selected);
		}
		$smarty->assign('databases',$databases);
		$smarty->assign('mysql_id',$mysql_id);
	}

}
else if($f==4) {
	$objDBConnection       = new DBConnection();
	$objMyStatusVars 	= new MyStatusVars($objDBConnection);
	$variablesList 	= $objMyStatusVars->getListOfVariables();
	if($_POST['submit'] == 'add') {
		$variables = $_POST['variables'];
		$objMyStatusVars->addVariables($variables);
		$success = "Selected Status Variables Configured";
	}
	$variables	= $objMyStatusVars->getSelectedVariables();

	foreach($variablesList as $key=>$variable) {
		if(in_array($variable,$variables)) {
			$selected = "selected";
		}
		else {
			$selected = "";
		}
		$variablesList[$key] = array('variable'=>$variable,'selected'=>$selected);
	}
	$smarty->assign('variables',$variablesList);
}
else if($f==5) {
	$objDBConnection		    = new DBConnection();
	$objCustomizedGraphs    = new CustomizedGraphs($objDBConnection);
	if($_POST['submit']) {
		$label	    = trim($_POST['name']);
		if($label) {
			$objCustomizedGraphs->add($label);
			$success    = "Label added succesfully. You can api to add data to MyTrend.";
		}
	}
	$data   = $objCustomizedGraphs->getList();
	$smarty->assign("data",$data);
	$smarty->assign("apikey",md5('mytrend'));
}
$smarty->assign('instanceArr',$instanceArr);
$smarty->assign("f",$f);
$smarty->assign("error",$error);
$smarty->assign("success",$success);
$smarty->assign('PageSelected',7);
$smarty->display('configure.html');
?>
