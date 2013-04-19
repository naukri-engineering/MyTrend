<?php
class CustomizedGraphs {
    private $objMySQL;
    public function __construct($objMySQL) {
	$this->objMySQL = $objMySQL;
    }
    public function add($label) {
	$label = addslashes($label);
	$sql = "insert ignore into `mytrend_customized_settings`(`label`) values('$label')";
	$this->objMySQL->query($sql);
    }
    public function getList($id='') {
	$data= array();
	if($id) {
	   $sql = "select * from `mytrend_customized_settings` where id='$id'";
	}
	else {
	   $sql = "select * from `mytrend_customized_settings`";
	}
	$res = $this->objMySQL->query($sql);
	while($row = mysql_fetch_assoc($res)) {
	    $data[] = array('id'=>$row['id'],'label'=>$row['label']);
	}
	return $data;
    }
    public function getData($id,$date1,$date2) {
	$dates = $this->dates_range($date1, $date2);
	$sql = "select * from `mytrend_customized_logs` where id='$id' and date>='$date1' and date<='$date2' order by date";
	$res = $this->objMySQL->query($sql);
	while($row = mysql_fetch_assoc($res)) {
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
