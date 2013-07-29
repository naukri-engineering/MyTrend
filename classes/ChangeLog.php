<?php
class ChangeLog {
	private $objDBConnection;
	public function __construct($objDBConnection) {
		$this->objDBConnection = $objDBConnection;
	}
	public function add($mysql_id,$date,$log) {
		$params = array('mysql_id'=>$mysql_id,'date'=>$date,'log'=>$log);
		$sql = "insert into `mytrend_change_log`(`mysql_id`,`date`,`log`) values(:mysql_id,:date,:log)";
		$this->objDBConnection->queryPDO($sql,array(),$params);
	}
	public function get($mysql_id,$date1,$date2) {
		$myStr = '';
		$params = array();
		foreach($mysql_id as $id) {
			$myStr .= ":mysql_id_$id,";	
			$params["mysql_id_$id"] = $id; 
		}
		$params['date1'] = $date1;
		$params['date2'] = $date2;
		$myStr = trim($myStr,',');
		$data= array();
		$sql = "select * from `mytrend_change_log` where `mysql_id` in($myStr) and `date`>=:date1 and `date`<=:date2 order by `mysql_id`,`date`";
		$res = $this->objDBConnection->queryPDO($sql,array(),$params);
		return $res;
	}
}
?>
