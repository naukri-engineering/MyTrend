<?php
/*
 * Cron   : This script should be scheduled in your crontab, which will run once in a day.
 * Author : Amardeep Vishwakarma
 */

//Check for cron argument(s)
if($argv[1] != 'D') { //Mandatory argument
		die('Sorry, Please pass the valid argument');
}
if($argv[2]) { //Optional argument - Email Id on which you will recieve the cron status.
		$email  = $argv[2];
}
$objMySQL       = new MySQL();
$objMyTrend = new MyTrend($objMySQL);
$instanceArr    = $objMyTrend->getMyInstance();
$date           = date("Y-m-d",strtotime('-1 day'));
$log            = '';

foreach($instanceArr as $instance) {
		$mysql_id	= $instance['mysql_id'];
		$host		= $instance['host'];
		$port		= $instance['port'];
		$username	= $instance['username'];
		$password	= $instance['password'];
		$ignoreDbs  = array();
		//Get this list of databases which needs to be ignored from the stats
		$ignoreDbs  = $objMyTrend->getIgnoreDatabase($mysql_id);
		$dCount = 0;
		$tCount = 0;
		if($connection = mysql_connect("$host:$port",$username,$password)) {
				//Fetch list of databases.
				$sql = "show databases";
				$res = mysql_query($sql);
				while($row = mysql_fetch_array($res)) {
						$database = $row['Database'];
						if(in_array($database,$ignoreDbs))
								continue;
						$dCount++;
						$conn = mysql_connect("$host:$port",$username,$password);
						mysql_select_db($database,$conn);
						//Fetch the status of each table (Row count and table size)
						$sql = "show table status";
						$res2 = mysql_query($sql);
						while($row2 = mysql_fetch_array($res2)) {
								$Rows   		= $row2['Rows'];
								$Name         	= $row2['Name'];
								$Data_length  	= $row2['Data_length'];
								$Index_length 	= $row2['Index_length'];
								//Add the data to storage tables.
								$objMyTrend->addToTables($mysql_id,$database,$Name,$date,$Rows,$Data_length,$Index_length);
								$tCount++;
						}
					mysql_close($conn); //Close MySQL Connection
				}
			mysql_close($connection); //Close MySQL Connection
		}
		$log .= "Instance : ".$mysql_id." ($host:$port) => ";
		$log .= "Databases : $dCount, Tables : $tCount"."\n\r";
}

$objMyTrend->addToOtherTables($date);

//MySQL Status Variables.
$objMyStatusVars    = new MyStatusVars($objMySQL);
//Get list of selected Variables.
$variables		= $objMyStatusVars->getSelectedVariables();
$objMySQL->close();
if($variables) {
		foreach($instanceArr as $instance) {
				$mysql_id       = $instance['mysql_id'];
				$dbSettings     = $objMyTrend->getDatabaseSettings($mysql_id);
				$objMyStatusVars    = new MyStatusVars($objMySQL,$dbSettings);
				//Get global status
				$allStatus		= $objMyStatusVars->getGlobalStatus();
				$objMySQL->close();
				foreach($variables as $variable) {
						$val = $allStatus[$variable];		
						//Add the status of status log table
						$objMyStatusVars->addToStatusLog($mysql_id,$variable,$val);
				}
				$objMySQL->close();
				$objMyStatusVars->cleanStatusLog($mysql_id);
				$objMySQL->close();
		}
}
if($email) {
		mail($email,"MyTrend Cron Status - $date",$log);
}

//AutoLoad function loading dependant classes
function __autoload($class_name) {
		require 'classes/'.$class_name . '.php';
}
?>
