<?php
/*
 * MySQL Status Variables.
 * Author : Amardeep Vishakarma
 */
class MyStatusVars {
	private $dbSettings;
	private $objDBConnection;
	/*
	 * Constructor.
	 */
	public function __construct($objDBConnection,$dbSettings='') {
		$this->objDBConnection     = $objDBConnection;
		$this->dbSettings   = $dbSettings;
	}
	/*
	 * Get a list of MySQL Status Variables.
	 */
	public function getListOfVariables() {
		$ignoreVar = array('Uptime','Threads_running','Threads_created','Threads_connected','Threads_cached','Open_tables','Open_files','Innodb_buffer_pool_pages_free','Innodb_buffer_pool_pages_dirty','Innodb_buffer_pool_pages_data');
		$sql = "show global status";
		$res = $this->objDBConnection->queryPDO($sql);
		$var = array();	
		foreach($res as $row) {
			if(is_numeric($row['Value'])) {
				if(in_array($row['Variable_name'],$ignoreVar))
					continue;
				$var[] = $row['Variable_name'];	
			}
		}
		return $var;
	}
	/* 
	 * Add selected MySQL Varuables to mytrend_selected_variables table
	 */
	public function addVariables($variables) {
		$sql = "delete from mytrend_selected_variables";
		$this->objDBConnection->queryPDO($sql);
		foreach($variables as $variable) {
			$sql = "insert ignore into mytrend_selected_variables(name) values(:variable)";
			$params = array('variable'=>$variable);
			$this->objDBConnection->queryPDO($sql,array(),$params);
		}
	}
	/* 
	 * Get a list of selected MySQL Variables.
	 */
	public function getSelectedVariables() {
		$sql = "select name from mytrend_selected_variables";
		$res = $this->objDBConnection->queryPDO($sql);
		$var = array();
		foreach($res as $row) {
			$var[] = $row['name'];	
		}
		return $var;
	}
	/*
	 * Get global Variables.
	 */ 
	public function getGlobalVariables() {
		$sql = "show global variables";
		$res = $this->objDBConnection->queryPDO($sql,$this->dbSettings);
		$arr = array();
		foreach($res as $row) {
			$arr[$row['Variable_name']] = $row['Value'];
		}
		return $arr;
	}
	/*
	 * Get global status.
	 */ 
	public function getGlobalStatus() {
		$sql = "show global status";
		$res = $this->objDBConnection->queryPDO($sql,$this->dbSettings);
		$arr = array();
		foreach($res as $row) {
			$arr[$row['Variable_name']] = $row['Value'];
		}
		return $arr;
	}
	/*
	 * Get server configuration variable stored in database (date-wise)
	 */
	public function getServerConfigVariables($mysql_id,$date) {
		$sql = "select config_data from mytrend_server_configs where mysql_id=:mysql_id and date=:date";
		$params = array('mysql_id'=>$mysql_id,'date'=>$date);
		$res = $this->objDBConnection->row($sql,array(),$params);
		if(!count($res))
			return array();
		return unserialize($res['config_data']);
	}
	/*
	 * Add data to server config table.
	 */
	public function addServerConfigs($mysql_id,$globalVariables) {
		$date   = date("Y-m-d",strtotime('-1 day'));
		$globalVariables = serialize($globalVariables);
		$sql    = "insert ignore into mytrend_server_configs(`mysql_id`,`date`,`config_data`) values(:mysql_id,:date,:globalVariables)";
		$params = array('mysql_id'=>$mysql_id,'date'=>$date,'globalVariables'=>$globalVariables);
		$this->objDBConnection->queryPDO($sql,array(),$params);
	}
	/*
	 * Add data to MyTrend database.
	 */
	public function addToStatusLog($mysql_id,$name,$value) {
		$date   = date("Y-m-d",strtotime('-1 day'));
		$sql    = "insert ignore into mytrend_status_log(`mysql_id`,`date`,`name`,`value`) values(:mysql_id,:date,:name,:value)";
		$params = array('mysql_id'=>$mysql_id,'date'=>$date,'name'=>$name,'value'=>$value);
		$this->objDBConnection->queryPDO($sql,array(),$params);

		$pDate = date('Y-m-d',strtotime('-2 day'));
		$previousValue  = $this->getStatus($mysql_id,$name,$pDate);
		if($previousValue) {
			if($value<$previousValue)
				$newValue = $value;
			else
				$newValue = $value-$previousValue;
		}
		else {
			$newValue = 0;
		}
		$sql    = "insert ignore into mytrend_status_variables(`mysql_id`,`date`,`name`,`value`) values(:mysql_id,:date,:name,:newValue)";
		$params = array('mysql_id'=>$mysql_id,'date'=>$date,'name'=>$name,'newValue'=>$newValue);
		$this->objDBConnection->queryPDO($sql,array(),$params);
	}
	/*
	 * Get a a Value of MySQL Variable from Temporay Log table.
	 */
	public function getStatus($mysql_id,$name,$date) {
		$sql = "select value from mytrend_status_log where mysql_id=:mysql_id and name=:name and date=:date";
		$params = array('mysql_id'=>$mysql_id,'name'=>$name,'date'=>$date);
		$res = $this->objDBConnection->row($sql,array(),$params);
		return $res['value'];
	}
	/*
	 * Delete previous temporay data (Keep only last 7 days data)
	 */ 
	public function cleanStatusLog($mysql_id) {
		$date = date("Y-m-d",strtotime('-30 day'));
		$sql = "delete from mytrend_status_log where mysql_id=:mysql_id and date<:date";
		$params=array('mysql_id'=>$mysql_id,'date'=>$date);
		$this->objDBConnection->queryPDO($sql,array(),$params);
	}
	public function getCompleteStatus($v,$date) {
                $myIds = array();
                if(SERVER_GROUP) {
                    $sql = "SELECT mysql_id FROM `mytrend_mysql_instances` where group_name=:group_name";
                    $params = array('group_name'=>SERVER_GROUP);
                    $result = $this->objDBConnection->queryPDO($sql,array(),$params);
                    foreach($result as $res) {
                        $myIds[] = $res['mysql_id'];
                    }  
                }

		$sql = "select mysql_id,value from mytrend_status_variables where name=:v and date=:date";
		$params = array('v'=>$v,'date'=>$date);
		$res = $this->objDBConnection->queryPDO($sql,array(),$params);
		$data = array();
		foreach($res as $row) {
			$mysql_id   = $row['mysql_id'];
			$value      = $row['value'];
			if(SERVER_GROUP) { 
			    if(in_array($mysql_id,$myIds)) {
				$data[$mysql_id] = array('mysql_id'=>$mysql_id,'value'=>$value);	
			    }           
			}
			else {
			$data[$mysql_id] = array('mysql_id'=>$mysql_id,'value'=>$value);
			}
		}
		return $data;
	}
	public function getMySQLVariableStatus($mysql_id,$v,$date1,$date2) {
		$dates = $this->dates_range($date1, $date2);
		$sql = "select date,value from mytrend_status_variables where mysql_id=:mysql_id and name=:v and date>=:date1 and date<=:date2";
		$params = array('mysql_id'=>$mysql_id,'v'=>$v,'date1'=>$date1,'date2'=>$date2);
		$res = $this->objDBConnection->queryPDO($sql,array(),$params);
		foreach($res as $row) {
			$date   = $row['date'];
			$value  = $row['value'];
			$dates[$date] = array('date'=>$date,'value'=>$value);
		}
		return $dates;
	}
	/*
	 * Get a array of dates between two date ranges.
	 */
	private function dates_range($date1, $date2) {
		if ($date1<$date2)
		{
			$dates_range[$date1]=$date1;
			$date1=strtotime($date1);
			$date2=strtotime($date2);
			while ($date1!=$date2)
			{
				$date1=mktime(0, 0, 0, date("m", $date1), date("d", $date1)+1, date("Y", $date1));
				$date = date('Y-m-d', $date1);
				$dates_range[$date]=$date;
			}
		}
		foreach($dates_range as $date) {
			$dates_range[$date] = array('date'=>$date,'value'=>0);
		}
		return $dates_range;
	}
}
?>
