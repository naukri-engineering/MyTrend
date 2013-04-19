<?php
class ChangeLog {
    private $objMySQL;
    public function __construct($objMySQL) {
	$this->objMySQL = $objMySQL;
    }
    public function add($mysql_id,$date,$log) {
	$log = addslashes($log);
	$sql = "insert into `mytrend_change_log`(`mysql_id`,`date`,`log`) values('$mysql_id','$date','$log')";
	$this->objMySQL->query($sql);
    }
    public function get($mysql_id,$date1,$date2) {
	$mysql_id = implode(",",$mysql_id);
	$data= array();
	$sql = "select * from `mytrend_change_log` where `mysql_id` in($mysql_id) and `date`>='$date1' and `date`<='$date2' order by `mysql_id`,`date`";
	$res = $this->objMySQL->query($sql);
	while($row = mysql_fetch_assoc($res)) {
	    $data[] = $row;
	}
	return $data;
    }
}
?>
