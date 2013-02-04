<?php
/*
 * MySQL Status Variables.
 * Author : Amardeep Vishakarma
 */
class MyStatusVars {
		private $dbSettings;
		private $objMySQL;
		/*
		 * Constructor.
		 */
		public function __construct($objMySQL,$dbSettings='') {
				$this->objMySQL     = $objMySQL;
				$this->dbSettings   = $dbSettings;
		}
		/*
		 * Get a list of MySQL Status Variables.
		 */
		public function getListOfVariables() {
				$ignoreVar = array('Uptime','Threads_running','Threads_created','Threads_connected');
				$sql = "show global status";
				$res = $this->objMySQL->query($sql);
				$var = array();
				while($row = mysql_fetch_assoc($res)) {
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
				$this->objMySQL->query($sql);
				foreach($variables as $variable) {
						$sql = "insert ignore into mytrend_selected_variables(name) values('$variable')";
						$this->objMySQL->query($sql);
				}
		}
		/* 
		 * Get a list of selected MySQL Variables.
		 */
		public function getSelectedVariables() {
				$sql = "select * from mytrend_selected_variables";
				$res = $this->objMySQL->query($sql);
				$var = array();
				while($row = mysql_fetch_assoc($res)) {
						$var[] = $row['name'];	
				}
				return $var;
		}
		/*
		 * Get global status.
		 */ 
		public function getGlobalStatus() {
				$sql = "show global status";
				$res = $this->objMySQL->query($sql,$this->dbSettings);
				$arr = array();
				while($row = mysql_fetch_assoc($res)) {
						$arr[$row['Variable_name']] = $row['Value'];
				}
				return $arr;
		}
		/*
		 * Add data to MyTrend database.
		 */
		public function addToStatusLog($mysql_id,$name,$value) {
				$date   = date("Y-m-d",strtotime('-1 day'));
				$sql    = "insert ignore into mytrend_status_log(`mysql_id`,`date`,`name`,`value`) values('$mysql_id','$date','$name','$value')";
				$this->objMySQL->query($sql);

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
				$sql    = "insert ignore into mytrend_status_variables(`mysql_id`,`date`,`name`,`value`) values('$mysql_id','$date','$name','$newValue')";
				$this->objMySQL->query($sql);
		}
		/*
		 * Get a a Value of MySQL Variable from Temporay Log table.
		 */
		public function getStatus($mysql_id,$name,$date) {
				$sql = "select value from mytrend_status_log where mysql_id='$mysql_id' and name='$name' and date='$date'";
				$res = $this->objMySQL->query($sql);
				$row = mysql_fetch_array($res);
				return $row['value'];
		}
		/*
		 * Delete previous temporay data (Keep only last 7 days data)
		 */ 
		public function cleanStatusLog($mysql_id) {
				$date = date("Y-m-d",strtotime('-7 day'));
				$sql = "delete from mytrend_status_log where mysql_id='$mysql_id' and date<'$date'";
				$this->objMySQL->query($sql);
		}
		public function getCompleteStatus($v,$date) {
				$sql = "select mysql_id,value from mytrend_status_variables where name='$v' and date='$date'";
				$res = $this->objMySQL->query($sql);
				$data = array();
				while($row = mysql_fetch_array($res)) {
						$mysql_id   = $row['mysql_id'];
						$value      = $row['value'];
						$data[$mysql_id] = array('mysql_id'=>$mysql_id,'value'=>$value);
				}
				return $data;
		}
		public function getMySQLVariableStatus($mysql_id,$v,$date1,$date2) {
				$dates = $this->dates_range($date1, $date2);
				$sql = "select date,value from mytrend_status_variables where mysql_id='$mysql_id' and name='$v' and date>='$date1' and date<='$date2'";
				$res = $this->objMySQL->query($sql);
				while($row = mysql_fetch_array($res)) {
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
