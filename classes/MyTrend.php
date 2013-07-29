<?php
/*
 * MyTrend Class - Deals with the data stored in MyTrend Database
 * Author : Amardeep Vishwakarma
 */
class MyTrend {
	private $objDBConnection;
	/*
	 * Constructor
	 */
	public function __construct($objDBConnection) {
		$this->objDBConnection = $objDBConnection;
	}
	public function setDataTables() {
	}
	/*
	 *  Returns an array of MySQL Instances which are configured for the MyTrend data.
	 */
	public function getMyInstance($mysql_id='') {
		if($mysql_id) {
			$sql = "SELECT * FROM `mytrend_mysql_instances` WHERE mysql_id=:mysql_id";
			$params = array('mysql_id'=>$mysql_id);
			$result = $this->objDBConnection->queryPDO($sql,array(),$params);
		}
		else {
			$sql = "SELECT * FROM `mytrend_mysql_instances`";
			$result = $this->objDBConnection->queryPDO($sql,array());
		}
		return $result;
	}
	/*
	 * Checks and Add new MySQL Instance
	 */
	public function addMyInstance($name,$host,$port,$username,$password) {
		$sql = "SELECT COUNT(*) COUNT FROM `mytrend_mysql_instances` WHERE `host`=:host AND `port`=:port";
		$params = array('host'=>$host,'port'=>$port);
		$result = $this->objDBConnection->row($sql,array(),$params);
		if($result['COUNT'])
			return false;
		$sql = "insert INTO `mytrend_mysql_instances`(`name`,`host`,`port`,`username`,`password`) VALUES(:name,:host,:port,:username,:password)";
		$params = array('name'=>$name,'host'=>$host,'port'=>$port,'username'=>$username,'password'=>$password);
		$this->objDBConnection->queryPDO($sql,array(),$params);
		return true;
	}

	/*
	 * Update the Insformarion in mytrend_mysql_instances table
	 */
	public function updateMyInstance($mysql_id,$name,$host,$port,$username,$password) {
		$sql = "UPDATE `mytrend_mysql_instances` SET `name`=:name,`host`=:host,`port`=:port,`username`=:username,`password`=:password WHERE mysql_id=mysql_id";
		$params = array('name'=>$name,'host'=>$host,'port'=>$port,'username'=>$username,'password'=>$password,'mysql_id'=>$mysql_id);
		$this->objDBConnection->queryPDO($sql,array(),$params);
	}
	/*
	 * Get a list of Ignored Databases.
	 */
	public function getIgnoreDatabase($mysql_id) {
		$databases = array();
		$sql = "SELECT `database` FROM mytrend_ignore_database WHERE mysql_id=:mysql_id";
		$params = array('mysql_id'=>$mysql_id);
		$result = $this->objDBConnection->queryPDO($sql,array(),$params);
		$database = array();
		foreach($result as $row) {
			$database[] = $row['database'];	
		}
		return $database;
	}
	/*
	 * Update a list of databases which are Ignored from the MyTrend stats.
	 */
	public function updateIgnoreDatabase($mysql_id,$databases) {
		$sql = "DELETE FROM mytrend_ignore_database WHERE mysql_id=:mysql_id";
		$params = array('mysql_id'=>$mysql_id);
		$this->objDBConnection->queryPDO($sql,array(),$params);
		foreach($databases as $db) {
			$sql = "INSERT IGNORE INTO mytrend_ignore_database(`mysql_id`,`database`) VALUES(:mysql_id,:db)";
			$params = array('mysql_id'=>$mysql_id,'db'=>$db);
			$this->objDBConnection->queryPDO($sql,array(),$params);
		}
	}
	/*
	 * Get a list of databases.
	 */
	public function getDatabase($mysql_id) {
		if($mysql_id==-1)
			return array();
		$databaseSettings = $this->getDatabaseSettings($mysql_id);
		$sql = "show databases";
		$result = $this->objDBConnection->queryPDO($sql,$databaseSettings);
		foreach($result as $row) {
			$mysqlDatabaseArr[] = $row['Database'];
		}
		return $mysqlDatabaseArr;
	}
	/*
	 * Get a list of Tables.
	 */
	public function getTables($mysql_id,$database) {
		if($mysql_id==-1 || $database==-1)
			return array();
		$databaseSettings = $this->getDatabaseSettings($mysql_id);
		$databaseSettings['database'] = $database;
		$sql = "show tables";
		$result = $this->objDBConnection->queryPDO($sql,$databaseSettings);
		$tablesArr = array();
		foreach($result as $row) {
		    $row = array_values($row);
		    $tablesArr[] = $row[0];
		}
		return $tablesArr;
	}
	/*
	 * Get data for Graphs - Instance
	 */
	public function getGraph_Instance($mysql_id,$date1,$date2) {
		$dates = $this->dates_range($date1, $date2);
		$params = array('mysql_id'=>$mysql_id,'date1'=>$date1,'date2'=>$date2);
		$res = $this->getData('mytrend_data_instance',"mysql_id=:mysql_id and `date`>=:date1 and  `date`<=:date2",$params);
		foreach($res as $row) {
			$date           = $row['date'];
			$data_length    = $row['data_length'];
			$index_length   = $row['index_length'];
			$data_size      = $data_length+$index_length;
			$dates[$date] = array('date'=>$date,'data_size'=>$data_size,'mysql_id'=>$row['mysql_id']);
		}
		return $dates;
	}
	/*
	 * Get data for Graphs - Database
	 */
	public function getGraph_Database($mysql_id,$database,$date1,$date2) {
		$params = array('mysql_id'=>$mysql_id,'database'=>$database,'date1'=>$date1,'date2'=>$date2);
		$res = $this->getData('mytrend_data_database',"mysql_id=:mysql_id and `database`=:database and `date`>=:date1 and `date`<=:date2",$params);
		foreach($res as $row) {
			$date           = $row['date'];
			$data_length    = $row['data_length'];
			$index_length   = $row['index_length'];
			$data_size      = $data_length+$index_length;
			$dates[$date] = array('date'=>$date,'data_size'=>$data_size,'database'=>$row['database']);
		}
		return $dates;
	}
	/*
	 * Get data for Graphs - Table
	 */
	public function getGraph_Table($mysql_id,$database,$table,$date1,$date2) {
		$params = array('mysql_id'=>$mysql_id,'database'=>$database,'table'=>$table,'date1'=>$date1,'date2'=>$date2);
		$res = $this->getData('mytrend_data_table',"mysql_id=:mysql_id and `database`=:database and `table`=:table and `date`>=:date1 and `date`<=:date2",$params);
		foreach($res as $row) {
			$date           = $row['date'];
			$data_length    = $row['data_length'];
			$index_length   = $row['index_length'];
			$data_size      = $data_length+$index_length;
			$dates[$date] = array('date'=>$date,'rows'=>$row['rows'],'data_size'=>$data_size,'table'=>$row['table']);
		}
		return $dates;
	}
	/* 
	 * Get data for MyTrend Stats - Instance
	 */
	public function getStats_InstanceData($date) {
		$params = array('date'=>$date);
		$res = $this->getData('mytrend_data_instance',"`date`=:date",$params);
		$data = array();
		foreach($res as $row) {
			$data[$row['mysql_id']] = array('date'=>$date,'data_size'=>$row['data_length']+$row['index_length'],'mysql_id'=>$row['mysql_id']);
		}
		return $data;
	}
	/*
	 * Get data for MyTrend Stats - Database
	 */
	public function getStats_DatabaseData($mysql_id,$date) {
		$params = array('mysql_id'=>$mysql_id,'date'=>$date);
		$res = $this->getData('mytrend_data_database',"mysql_id=:mysql_id and `date`=:date",$params);
		$data = array();
		foreach($res as $row) {
			$data[$row['database']] = array('date'=>$date,'data_size'=>$row['data_length']+$row['index_length'],'database'=>$row['database']);
		}
		return $data;
	}
	/*
	 * Get data for MyTrend Stats - Table
	 */
	public function getStats_TableData($mysql_id,$database,$date) {
		$params = array('mysql_id'=>$mysql_id,'database'=>$database,'date'=>$date);
		$res = $this->getData('mytrend_data_table',"mysql_id=:mysql_id and `database`=:database and `date`=:date",$params);
		$data = array();
		foreach($res as $row) {
			$data[$row['table']] = array('date'=>$date,'rows'=>$row['rows'],'data_size'=>$row['data_length']+$row['index_length'],'table'=>$row['table']);
		}
		return $data;
	}
	private function getData($table,$where,$params) {
		$sql = "select * from $table where $where";
		$res = $this->objDBConnection->queryPDO($sql,array(),$params);
		return $res;
	}
	/*
	 * Get MySQL Connection settings
	 */
	public function getDatabaseSettings($mysql_id) {
		$database = $this->getMyInstance($mysql_id);
		return $database[0];
	}
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
			$dates_range[$date] = array('date'=>$date,'rows'=>0,'data_size'=>0);
		}
		return $dates_range;
	}
	/*
	 * Add MyTrend data - Table Level
	 */
	public function addToTables($mysql_id,$database,$Name,$date,$Rows,$Data_length,$Index_length) {
		$sql = "INSERT INTO mytrend_data_table(`mysql_id`,`database`,`table`,`date`,`rows`,`data_length`,`index_length`) values(:mysql_id,:database,:Name,:date,:Rows,:Data_length,:Index_length)";
		$params = array('mysql_id'=>$mysql_id,'database'=>$database,'Name'=>$Name,'date'=>$date,'Rows'=>$Rows,'Data_length'=>$Data_length,'Index_length'=>$Index_length);
		$this->objDBConnection->queryPDO($sql,array(),$params);
		$this->objDBConnection->close();
	}
	/*
	 * Add MyTrend Data - Database & Instance
	 */
	public function addToOtherTables($date) {
		$sql = "INSERT INTO mytrend_data_database SELECT `mysql_id`,`database`,`date`,sum(`data_length`) `data_length`,sum(`index_length`) `index_length` from `mytrend_data_table` WHERE `date`=:date GROUP BY `mysql_id`,`database`";
		$params = array('date'=>$date);
		$this->objDBConnection->queryPDO($sql,array(),$params);

		$sql = "INSERT INTO mytrend_data_instance SELECT `mysql_id`,`date`,sum(`data_length`) `data_length`,sum(`index_length`) `index_length` FROM mytrend_data_database WHERE `date`=:date GROUP BY `mysql_id`";
		$params = array('date'=>$date);
		$this->objDBConnection->queryPDO($sql,array(),$params);
	}
}
?>
