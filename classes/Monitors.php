<?php
class Monitors {
    private $objDBConnection;
    /*
     * Constructor
     */
    public function __construct($objDBConnection,$dbSettings) {
	$this->objDBConnection 	= $objDBConnection;
	$this->dbSettings 	= $dbSettings;
    }
    public function getMonitorsData($f,$allVariables,$allStatus) {
	$arr = array();
	//General Info
	if($f==1) {
	    $arr[] = array("label"=>"Version","value"=>$allVariables['version']);
	    $arr[] = array("label"=>"Running for","value"=>$this->convertIntoDays($allStatus['Uptime']));
	    $arr[] = array("label"=>"Start time","value"=>date("d M Y H:i:s",strtotime('-'.$allStatus['Uptime'].' seconds')));
	    $arr[] = array("label"=>"Default storage engine","value"=>$allVariables['storage_engine']);
	    $arr[] = array("label"=>"Uptime since FLUSH STATUS","value"=>$this->convertIntoDays($allStatus['Uptime_since_flush_status']));
	}
	//Security
	if($f==2) {
	    $arr[] = array("label"=>"Users with wildcards in host value","value"=>$this->accessUser(1));
	    $arr[] = array("label"=>"Number of anonymous users","value"=>$this->accessUser(2));
	    $arr[] = array("label"=>"Number of 'root' users","value"=>$this->accessUser(3));
	    $arr[] = array("label"=>"'root' user can login remotely","value"=>$this->accessUser(4));
	    $arr[] = array("label"=>"'root' user without password","value"=>$this->accessUser(5));
	    //$arr[] = array("label"=>"An insecure database using 'test' in its name is detected","value"=>	Is_test_db_exists
	    $arr[] = array("label"=>"Accounts without passwords","value"=>$this->accessUser(6));
	    $arr[] = array("label"=>"Need to resolve host name?","value"=>$allVariables['skip_name_resolve']);
	    $arr[] = array("label"=>"Old Password Authentication detected","value"=>$allVariables['secure_auth']);
	    $arr[] = array("label"=>"Insecure Password Generation detected","value"=>$allVariables['old_passwords']);
	    $arr[] = array("label"=>"Data integrity check enabled by server?","value"=>$allVariables['sql_mode']);
	    $arr[] = array("label"=>"LOAD DATA LOCAL option detected","value"=>$allVariables['local_infile']);
	    $arr[] = array("label"=>"Security Alterations Alert: user privileges granted","value"=>$allStatus['Com_grant']);
	    $arr[] = array("label"=>"Security Alterations Alert: user privileges revoked","value"=>$allStatus['Com_revoke'] + $allStatus['Com_revoke_all']);
	    $arr[] = array("label"=>"Symlinks are enabled","value"=>$allVariables['have_symlink']);
	    $arr[] = array("label"=>"Users can view all databases on MySQL server","value"=>$allVariables['skip_show_database']);
	}
	//Commands & Schema Change	
	if($f==3) {
	    $arr[] = array("label"=>"Total no. of Admin Commands","value"=>$this->bytesToSize($allStatus['Com_admin_commands']));
	    $arr[] = array("label"=>"Change DBs","value"=>$this->bytesToSize($allStatus['Com_change_db']));
	    $arr[] = array("label"=>"Prepare SQL","value"=>$allStatus['Com_prepare_sql']);	
	    $arr[] = array("label"=>"Dealloc SQL","value"=>$allStatus['Com_dealloc_sql']);
	    $arr[] = array("label"=>"Close Prepare SQL","value"=>$this->bytesToSize($allStatus['Com_stmt_close']));
	    $arr[] = array("label"=>"Lock Tables","value"=>$allStatus['Com_lock_tables']);
	    $arr[] = array("label"=>"Show Create Tables","value"=>$allStatus['Com_show_create_table']);
	    $arr[] = array("label"=>"Show Fields","value"=>$this->bytesToSize($allStatus['Com_show_fields']));
	    $arr[] = array("label"=>"Number of database alterations","value"=>$allStatus['Com_alter_db']);
	    $arr[] = array("label"=>"Number of databases created","value"=>$allStatus['Com_create_db']);
	    $arr[] = array("label"=>"Number of databases dropped","value"=>$allStatus['Com_drop_db']);
	    $arr[] = array("label"=>"Number of functions created","value"=>$allStatus['Com_create_function']);
	    $arr[] = array("label"=>"Number of functions dropped","value"=>$allStatus['Com_drop_function']);
	    $arr[] = array("label"=>"Number of indexes created","value"=>$allStatus['Com_create_index']);
	    $arr[] = array("label"=>"Number of indexes dropped","value"=>$allStatus['Com_drop_index']);
	    $arr[] = array("label"=>"Number of table alterations","value"=>$allStatus['Com_alter_table']);
	    $arr[] = array("label"=>"Number of tables created","value"=>$allStatus['Com_create_table']);
	    $arr[] = array("label"=>"Number of tables dropped","value"=>$allStatus['Com_drop_table']);
	    $arr[] = array("label"=>"Number of users dropped","value"=>$allStatus['Com_drop_user']);
	}
	//Current Connections
	if($f==4) {
	    $arr[] = array("label"=>"Max allowed","value"=>$allVariables['max_connections']);
	    $arr[] = array("label"=>"Open connections",	"value"=>$allStatus['Threads_connected']);
	    $arr[] = array("label"=>"Connection usage","value"=>round(($allStatus['Threads_connected'] / $allVariables['max_connections'])*100,2)." %");
	    $arr[] = array("label"=>"Currently running threads","value"=>$allStatus['Threads_running']);
	    $arr[] = array("label"=>"Highest no. of concurrent connections","value"=>$allStatus['Max_used_connections']);
	    $arr[] = array("label"=>"Idle time after which a client is disconnected","value"=>$this->convertIntoDays($allVariables['wait_timeout']));
	    $arr[] = array("label"=>"Max number of interrupts before host is blocked","value"=>$allVariables['max_connect_errors']);
	    $arr[] = array("label"=>"Connect timeout","value"=>$this->convertIntoDays($allVariables['connect_timeout']));
	}
	//Connection History
	if($f==5) {
	    $arr[] = array("label"=>"Attempts","value"=>$this->bytesToSize($allStatus['Connections']));
	    $arr[] = array("label"=>"Successful	Connections","value"=>$this->bytesToSize($allStatus['Connections']-$allStatus['Aborted_connects']));
	    $arr[] = array("label"=>"Percentage of max allowed reached","value"=>round(($allStatus['Max_used_connections']/$allVariables['max_connections'])*100,2)." %");
	    $arr[] = array("label"=>"Refused","value"=>$this->bytesToSize($allStatus['Aborted_connects']));
	    $arr[] = array("label"=>"Percentage of refused connections","value"=>round(($allStatus['Aborted_connects']/$allStatus['Connections'])*100,2)." %");
	    $arr[] = array("label"=>"Terminated abruptly","value"=>$this->bytesToSize($allStatus['Aborted_clients']));
	    $arr[] = array("label"=>"Bytes received from all clients","value"=>$this->bytesToSize($allStatus['Bytes_received']));
	    $arr[] = array("label"=>"Bytes sent to all clients","value"=>$this->bytesToSize($allStatus['Bytes_sent']));
	}
	//Threads
	if($f==6) {
	    $arr[] = array("label"=>"Number of threads that can be cached","value"=>$allVariables['thread_cache_size']);
	    $arr[] = array("label"=>"Number of threads in cache","value"=>$allStatus['Threads_cached']);
	    $arr[] = array("label"=>"Threads created to handle connections","value"=>$this->bytesToSize($allStatus['Threads_created']));
	    $arr[] = array("label"=>"Thread cache hit rate","value"=>round(($allStatus['Threads_created']/$allStatus['Connections'])*100,2)." %");
	    $arr[] = array("label"=>"Min. launch time for a thread to be considered slow","value"=>$this->convertIntoDays($allVariables['slow_launch_time']));
	    $arr[] = array("label"=>"No. of slow launch threads","value"=>$allStatus['Slow_launch_threads']);
	}
	//Table Cache & Locks
	if($f==7) {
	    $arr[] = array("label"=>"Number of tables that can be cached (for all clients)","value"=>$allVariables['table_open_cache']);
	    $arr[] = array("label"=>"Tables currently open","value"=>$allStatus['Open_tables']);
	    $arr[] = array("label"=>"Number of table cache misses","value"=>$this->bytesToSize($allStatus['Opened_tables']));
	    $arr[] = array("label"=>"Locks Acquired immediately","value"=>$this->bytesToSize($allStatus['Table_locks_immediate']));
	    $arr[] = array("label"=>"Wait was needed","value"=>$this->bytesToSize($allStatus['Table_locks_waited']));
	    $arr[] = array("label"=>"Lock contention","value"=>round((($allStatus['Table_locks_waited'] / ($allStatus['Table_locks_waited'] + $allStatus['Table_locks_immediate'])))*100,2)." %");
	    //$arr[] = array("label"=>"Lock wait timeout","value"=>lock_wait_timeout
	}
	//Binary Log
	if($f==8) {
	    $arr[] = array("label"=>"Enabled?","value"=>$allVariables['log_bin']);
	    $arr[] = array("label"=>"Binary log format","value"=>$allVariables['binlog_format']);
	    $arr[] = array("label"=>"Binary log cache size","value"=>$this->bytesToSize($allVariables['binlog_cache_size']));
	    $arr[] = array("label"=>"Transactions that used cache","value"=>$this->bytesToSize($allStatus['Binlog_cache_use']));
	    $arr[] = array("label"=>"Transactions that got saved in temporary file","value"=>$allStatus['Binlog_cache_disk_use']);
	    $arr[] = array("label"=>"Percentage of transactions that went in temporary file","value"=>round(($allStatus['Binlog_cache_disk_use'] / $allStatus['Binlog_cache_use'])*100,2)." %");
	    $arr[] = array("label"=>"Synchronized to disk at each write?","value"=>($allVariables['sync_binlog']>0)?'Yes':'No');
	    $arr[] = array("label"=>"Days before purging binary logs","value"=>$allVariables['expire_logs_days']);
	    $arr[] = array("label"=>"Maximum binary log file size", "value"=>$this->bytesToSize($allVariables['max_binlog_size']));
	}
	//Replication
	if($f==9) {
	    $slave = $this->getSlaveStatus();
	    $arr[] = array("label"=>"Slave I/O State","value"=>$slave['Slave_IO_State']);
	    $arr[] = array("label"=>"Master Host","value"=>$slave['Master_Host']);
	    $arr[] = array("label"=>"Master User","value"=>$slave['Master_User']);
	    $arr[] = array("label"=>"Master Port","value"=>$slave['Master_Port']);
	    $arr[] = array("label"=>"Connect_Retry","value"=>$slave['Connect_Retry']);
	    $arr[] = array("label"=>"Master Log File","value"=>$slave['Master_Log_File']);
	    $arr[] = array("label"=>"Read_Master_Log_Pos","value"=>$slave['Read_Master_Log_Pos']);
	    $arr[] = array("label"=>"Relay_Log_File","value"=>$slave['Relay_Log_File']);
	    $arr[] = array("label"=>"Relay_Log_Pos","value"=>$slave['Relay_Log_Pos']);
	    $arr[] = array("label"=>"Relay_Master_Log_File","value"=>$slave['Relay_Master_Log_File']);
	    $arr[] = array("label"=>"Slave I/O Running","value"=>$slave['Slave_IO_Running']);
	    $arr[] = array("label"=>"Slave SQL Running","value"=>$slave['Slave_SQL_Running']);
	    $arr[] = array("label"=>"Replicate_Do_DB","value"=>$slave['Replicate_Do_DB']);
	    $arr[] = array("label"=>"Replicate_Ignore_DB","value"=>$slave['Replicate_Ignore_DB']);
	    $arr[] = array("label"=>"Replicate_Do_Table","value"=>$slave['Replicate_Do_Table']);
	    $arr[] = array("label"=>"Replicate_Ignore_Table","value"=>$slave['Replicate_Ignore_Table']);
	    $arr[] = array("label"=>"Replicate_Wild_Do_Table","value"=>$slave['Replicate_Wild_Do_Table']);
	    $arr[] = array("label"=>"Replicate_Wild_Ignore_Table","value"=>$slave['Replicate_Wild_Ignore_Table']);
	    $arr[] = array("label"=>"Last_Errno","value"=>$slave['Last_Errno']);
	    $arr[] = array("label"=>"Last_Error","value"=>$slave['Last_Error']);
	    $arr[] = array("label"=>"Skip_Counter","value"=>$slave['Skip_Counter']);
	    $arr[] = array("label"=>"Exec_Master_Log_Pos","value"=>$slave['Exec_Master_Log_Pos']);
	    $arr[] = array("label"=>"Relay_Log_Space","value"=>$slave['Relay_Log_Space']);
	    $arr[] = array("label"=>"Until_Condition","value"=>$slave['Until_Condition']);
	    $arr[] = array("label"=>"Until_Log_File","value"=>$slave['Until_Log_File']);
	    $arr[] = array("label"=>"Until_Log_Pos","value"=>$slave['Until_Log_Pos']);
	    //$arr[] = array("label"=>"Master_SSL_Allowed","value"=>$slave['Master_SSL_Allowed']);
	    //$arr[] = array("label"=>"Master_SSL_CA_File","value"=>$slave['Master_SSL_CA_File']);
	    //$arr[] = array("label"=>"Master_SSL_CA_Path","value"=>$slave['Master_SSL_CA_Path']);
	    //$arr[] = array("label"=>"Master_SSL_Cert","value"=>$slave['Master_SSL_Cert']);
	    //$arr[] = array("label"=>"Master_SSL_Cipher","value"=>$slave['Master_SSL_Cipher']);
	    //$arr[] = array("label"=>"Master_SSL_Key","value"=>$slave['Master_SSL_Key']);
	    $arr[] = array("label"=>"Seconds Behind Master","value"=>$slave['Seconds_Behind_Master']);
	    $arr[] = array("label"=>"Master_SSL_Verify_Server_Cert","value"=>$slave['Master_SSL_Verify_Server_Cert']);
	    $arr[] = array("label"=>"Last_IO_Errno","value"=>$slave['Last_IO_Errno']);
	    $arr[] = array("label"=>"Last_IO_Error","value"=>$slave['Last_IO_Error']);
	    $arr[] = array("label"=>"Last_SQL_Errno","value"=>$slave['Last_SQL_Errno']);
	    $arr[] = array("label"=>"Last_SQL_Error","value"=>$slave['Last_SQL_Error']);
	    $arr[] = array("label"=>"Replicate_Ignore_Server_Ids","value"=>$slave['Replicate_Ignore_Server_Ids']);
	    $arr[] = array("label"=>"Master_Server_Id","value"=>$slave['Master_Server_Id']);
	    if(!$slave) {
		foreach($arr as $key=>$value) {
		    $arr[$key] = array("label"=>$value['label'],"value"=>"(n/a)");
		}
	    }

	}
	//Index Usage
	if($f==10) {
	    $arr[] = array("label"=>"Percentage of full table scans","value"=>round((($allStatus['Handler_read_rnd_next'] + $allStatus['Handler_read_rnd']) / ($allStatus['Handler_read_rnd_next'] + $allStatus['Handler_read_rnd'] + $allStatus['Handler_read_first'] + $allStatus['Handler_read_next'] + $allStatus['Handler_read_key'] + $allStatus['Handler_read_prev']))*100,2)," %");

	    $arr[] = array("label"=>"Buffer for full table scans (per client)","value"=>$this->bytesToSize($allVariables['read_buffer_size']));
	    $arr[] = array("label"=>"SELECTs requiring full table scan","value"=>$this->bytesToSize($allStatus['Select_scan']));
	    $arr[] = array("label"=>"Buffer for joins requiring full table scan (per client)","value"=>$this->bytesToSize($allVariables['join_buffer_size']));
	    $arr[] = array("label"=>"Joins requiring full scan of second and subsequent tables","value"=>$allStatus['Select_full_join']);
	    $arr[] = array("label"=>"Joins that reevaluate index selection for each row in a join","value"=>$allStatus['Select_range_check']);
	}
	//Statements
	if($f==11) {
	    $arr[] = array("label"=>"All statements","value"=>$this->bytesToSize($allStatus['Questions']));
	    $arr[] = array("label"=>"SELECTs","value"=>$this->bytesToSize($allStatus['Com_select'] + $allStatus['Qcache_hits']));
	    $arr[] = array("label"=>"INSERTs","value"=>$this->bytesToSize($allStatus['Com_insert'] + $allStatus['Com_replace']));
	    $arr[] = array("label"=>"UPDATEs","value"=>$this->bytesToSize($allStatus['Com_update']));
	    $arr[] = array("label"=>"DELETEs","value"=>$this->bytesToSize($allStatus['Com_delete']));

	    $arr[] = array("label"=>"Total rows returned","value"=> $this->bytesToSize($allStatus['Handler_read_first'] + $allStatus['Handler_read_key'] + $allStatus['Handler_read_next'] + $allStatus['Handler_read_prev'] + $allStatus['Handler_read_rnd'] + $allStatus['Handler_read_rnd_next'] + $allStatus['Sort_rows']));
	    $arr[] = array("label"=>"Total rows returned via indexes","value"=>	$this->bytesToSize($allStatus['Handler_read_first'] + $allStatus['Handler_read_key'] + $allStatus['Handler_read_next'] + $allStatus['Handler_read_prev']));
	    $arr[] = array("label"=>"Avg rows per query","value"=>round(($allStatus['Handler_read_first'] + $allStatus['Handler_read_key'] + $allStatus['Handler_read_next'] + $allStatus['Handler_read_prev'] + $allStatus['Handler_read_rnd'] + $allStatus['Handler_read_rnd_next'] + $allStatus['Sort_rows'])/$allStatus['Questions'],2));
	    //$arr[] = array("label"=>"Percentage of rows returned using indexes","value"=>($allStatus['Handler_read_first'] + $allStatus['Handler_read_key'] + $allStatus['Handler_read_next'] + $allStatus['Handler_read_prev'])/($allStatus['Handler_read_first'] + $allStatus['Handler_read_key'] + $allStatus['Handler_read_next'] + $allStatus['Handler_read_prev'] + $allStatus['Handler_read_rnd'] + $allStatus['Handler_read_rnd_next'] + $allStatus['Sort_rows'])*100);
	    $arr[] = array("label"=>"Max Prepared statements configuration","value"=>$this->bytesToSize($allVariables['max_prepared_stmt_count']));

	}
	//Misc.
	if($f==12) {
	    $arr[] = array("label"=>"MyISAM auto recovery enabled?","value"=>($allVariables['myisam_recover_options']=='OFF')?'No':'Yes');
	    $arr[] = array("label"=>"Worst case memory requirement","value"=> $this->bytesToSize($allVariables['innodb_buffer_pool_size'] + $allVariables['key_buffer_size'] + $allVariables['max_connections'] * ($allVariables['sort_buffer_size'] + $allVariables['read_buffer_size'] + $allVariables['binlog_cache_size']) + $allVariables['max_connections'] * 2097152));
	    $arr[] = array("label"=>"INSERT DELAYED errors","value"=>$allStatus['Delayed_errors']);
	    $arr[] = array("label"=>"Transaction Isolation","value"=>$allVariables['tx_isolation']);
	    //$arr[] = array("label"=>"Portability issues because identifiers are case sensitive	---
	    //$arr[] = array("label"=>"Multiple threads used when repairing MyISAM tables		---
	    $arr[] = array("label"=>"Data being flushed to disk after each SQL statement?","value"=>$allVariables['flush']);
	    $arr[] = array("label"=>"Buffer for in-memory sorting (per client)","value"=>$this->bytesToSize($allVariables['sort_buffer_size']));
	    $arr[] = array("label"=>"Sort range","value"=>$this->bytesToSize($allStatus['Sort_range']));
	    $arr[] = array("label"=>"Sort scan","value"=>$this->bytesToSize($allStatus['Sort_scan']));
	    $arr[] = array("label"=>"Temporary files created because of insufficient sort_buffer_size","value"=>$this->bytesToSize($allStatus['Sort_merge_passes']));
	    $arr[] = array("label"=>"For fast rebuilds of MyISAM indexes","value"=>$this->bytesToSize($allVariables['myisam_sort_buffer_size']));
	    $arr[] = array("label"=>"For reading rows in sorted order after a sort operation","value"=>$this->bytesToSize($allVariables['read_rnd_buffer_size']));
	    $arr[] = array("label"=>"Interval between disk flushes","value"=>$allVariables['flush_time']);
	    $arr[] = array("label"=>"max open files","value"=>$this->bytesToSize($allVariables['open_files_limit']));
	}
	//Temporary Tables
	if($f==13) {
	    $arr[] = array("label"=>"Maximum size allowed (per client)","value"=>$this->bytesToSize($allVariables['tmp_table_size']));	
	    $arr[] = array("label"=>"Maximum size of a memory table","value"=>$this->bytesToSize($allVariables['max_heap_table_size']));
	    $arr[] = array("label"=>"Total tables created","value"=>$this->bytesToSize($allStatus['Created_tmp_tables']));
	    $arr[] = array("label"=>"Created on disk","value"=>$this->bytesToSize($allStatus['Created_tmp_disk_tables']));
	    $arr[] = array("label"=>"Disk:total ratio","value"=>round(($allStatus['Created_tmp_disk_tables'] / $allStatus['Created_tmp_tables'])*100,2)." %");
	}
	return $arr;	
    }
    //Get Slave Status.
    private function getSlaveStatus() {
	$sql = "show slave status";
	$res = $this->objDBConnection->query($sql,$this->dbSettings,0);		    
	$row = mysql_fetch_assoc($res);
	return $row;
    }
    //Convert into Days
    private function convertIntoDays($ss) {
	$s = $ss%60;
	$m = floor(($ss%3600)/60);
	$h = floor(($ss%86400)/3600);
	$d = floor(($ss%2592000)/86400);
	$M = floor($ss/2592000);
	$str = "";
	if($M)	$str .= "$M months, ";
	if($d)	$str .= "$d days, ";
	if($h)	$str .= "$h hrs, ";
	if($m)	$str .= "$m mins, ";
	if($s)	$str .= "$s secs";
	return 	rtrim($str,', ');
    }


    private function bytesToSize($bytes, $precision = 2)
    {  
	$kilobyte = 1024;
	$megabyte = $kilobyte * 1024;
	$gigabyte = $megabyte * 1024;
	$terabyte = $gigabyte * 1024;
	if (($bytes >= 0) && ($bytes < $kilobyte)) {
	    return $bytes . ' B';
	} elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
	    return round($bytes / $kilobyte, $precision) . ' KB';
	} elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
	    return round($bytes / $megabyte, $precision) . ' MB';
	} elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
	    return round($bytes / $gigabyte, $precision) . ' GB';
	} elseif ($bytes >= $terabyte) {
	    return round($bytes / $terabyte, $precision) . ' TB';
	} else {
	    return $bytes . ' B';
	}
    }
    private function accessUser($f) {
	$this->objDBConnection->close();
	if($f==1)   $sql = "select count(*) cnt from mysql.user where Host='' or Host='%'";
	if($f==2)   $sql = "select count(*) cnt from mysql.user where User=''";
	if($f==3)   $sql = "select count(*) cnt from mysql.user where User='root'";
	if($f==4)   $sql = "select count(*) cnt from mysql.user where Host='' or Host='%'";
	if($f==5)   $sql = "select count(*) cnt from mysql.user where User='root' and Password=''";
	if($f==6)   $sql = "select count(*) cnt from mysql.user where Password=''";
	$row = $this->objDBConnection->row($sql,$this->dbSettings);
	return $row['cnt'];
    }
}
?>
