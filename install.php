<?php
/*
 * The PHP page that serves all requests for installation.
 * Author : Amardeep Vishwakarma
 */
require_once "include.php";
require_once "classes/Install.php";
require_once "config/settings.php";

//Fetch the value from install.lock file
$install_lock_value = trim(file_get_contents('config/install.lock'));
if(!$install_lock_value)
	$install_lock_value = 1;

	//If all the steps of installation are done, redirect this to index.php.
	if($INSTALLED != '') {
		header('Location:index.php');
		die;
	}

if($_GET['action'] == 'cancel') {
	$install_lock_value = 0;
	$step = 0;
}
//---------------------------
//Step - 1 : # Verify requirements
//---------------------------
if($install_lock_value=='1') {
	$step = 1;
	//Check in the config directory has the write permission or not.
	if(!is_writable('config')) {
		$error = '<b>File system :</b> The directory config is not writable. To proceed with the installation, modify its permissions manually. <br/>For more information, see INSTALL.txt.<br/>Refresh the page after modifying the permissions.';
	}
	else if(!is_writable('config/settings.php')) {
		$error = '<b>Settings file :</b> The settings file is not writable. The installer requires write permissions to ./config/settings.php during the installation process. <br/>For more information, see INSTALL.txt.<br/>Refresh the page after modifying the permissions.';
	}
	else if(!is_writable('config/install.lock')) {
		$error = '<b>Lock file :</b> The lock file is not writable. The installer requires write permissions to ./config/install.lock during the installation process. <br/>For more information, see INSTALL.txt.<br/>Refresh the page after modifying the permissions.';
	}
	if($_GET['confirm']=='yes') {
		//Success - step 1
		file_put_contents('config/install.lock',2);
		$step = 2;
	}
}

//---------------------------
//Step - 2 : # Set up database
//---------------------------
if($install_lock_value=='2') {
	$step = 2;
	$smarty->assign('host','localhost');	//Set default host = localhost
	$smarty->assign('port','3306');			//Set default port = 3306
	if($_POST['submit']) {
		$database	= $_POST['database'];
		$host		= $_POST['host'];
		$port		= $_POST['port'];
		$username	= $_POST['username'];
		$password	= $_POST['password'];
		$errors		= array();
		//Basic Validations
		if(!$database)	$errors[] = 'Database name field is required.';
		if(!$host)		$errors[] = 'Database host field is required.';
		if(!$port)		$errors[] = 'Database port field is required.';
		if(!$username)	$errors[] = 'Database username field is required.';

		$smarty->assign('database',$database);
		$smarty->assign('host',$host);
		$smarty->assign('port',$port);
		$smarty->assign('username',$username);
		$smarty->assign('password',$password);

		if(!$errors) {
			if(mysql_connect("$host:$port",$username,$password)) {
				if(mysql_select_db($database)) {
					//Create the tables which are required by MyTrend
					$tables = createTables();
					foreach($tables as $table) {
						if(!mysql_query($table)) {
							$mysql_error = 1;
							break;
						}
					}
					//Create the config/settings.php file
					$settingsFile = createSettingsFile($host,$port,$username,$password,$database);
					file_put_contents('config/settings.php',$settingsFile);
					//Update the config/install.lock value
					file_put_contents('config/install.lock',3);
					$step = 3;
				}
				else {
					$mysql_error = 1;
				}
			}
			else {
				$mysql_error = 1;
			}
			if($mysql_error) {
				$errors[] = 'In order this to work, and to continue with the installation process, you must resolve all issues reported below.';
				$error = 'Failed to connect to your database server. The server reports the following message: <b>'.mysql_error().'</b>';
			}
		}
		$smarty->assign('errors',$errors);
	}
}

//---------------------------
//Step - 3 : # Choose Password
//---------------------------
if($install_lock_value=='3') {
	$step = 3;
	if($_POST['submit']) {
		$sa_password		= trim($_POST['sa_password']);	//SuperAdmin Password
		$a_password			= trim($_POST['a_password']);	//Admin Password

		$sa_c_password		= trim($_POST['sa_c_password']);	//Confirm Password
		$a_c_password		= trim($_POST['a_c_password']);	//Confirm Password

		//Basic Validations
		if(!$sa_password || !$a_password || strlen($sa_password)<6 || strlen($a_password)<6) {
			$error = 'Both the passwords are mandatory. 6 characters or more! Be tricky.';
		}
		elseif($sa_password != $sa_c_password) {
			$error = "Super Admin : The password you've confirmed does not match.";
		}
		elseif($a_password != $a_c_password) {
			$error = "Admin : The password you've confirmed does not match.";
		}
		else {
			//Update the passwords in mytrend_users table.
			$mySQLObj = new DBConnection();
			$sa_password 	= md5($sa_password);
			$a_password 	= md5($a_password);
			$sql = "INSERT IGNORE INTO `mytrend_users`(`role`,`password`) values('superadmin',:sa_password),('admin',:a_password)";
			$params = array('sa_password'=>$sa_password,'a_password'=>$a_password);
			$mySQLObj->queryPDO($sql,array(),$params);
			$settingsFile = createSettingsFile($databaseSettings['host'],$databaseSettings['port'],$databaseSettings['username'],$databaseSettings['password'],$databaseSettings['database'],"YES");
			file_put_contents('config/settings.php',$settingsFile);
			file_put_contents('config/install.lock',4);
			$step = 4;
		}
	}
}
//---------------------------
//Step - 4 : #Installtion Complete.
//---------------------------
if($install_lock_value=='4') {
	$step = 4;
}
$smarty->assign('error',$error);
$smarty->assign('step',$step);
$smarty->assign('DoNotDisplayLinks',1);
$smarty->display('install.html');
?>
