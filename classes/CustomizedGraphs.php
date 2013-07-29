<?php
class CustomizedGraphs {
	private $objDBConnection;
	public function __construct($objDBConnection) {
		$this->objDBConnection = $objDBConnection;
	}
	public function add($label) {
		$params = array('label'=>$label);
		$sql = "insert ignore into `mytrend_customized_settings`(`label`) values(:label)";
		$this->objDBConnection->queryPDO($sql,array(),$params);
	}
	public function getList($id='') {
		$data= array();
		if($id) {
			$params = array('id'=>$id);
			$sql = "select * from `mytrend_customized_settings` where id=:id";
			$res = $this->objDBConnection->queryPDO($sql,array(),$params);
		}
		else {
			$sql = "select * from `mytrend_customized_settings`";
			$res = $this->objDBConnection->queryPDO($sql);
		}
		foreach($res as $row) {
			$data[] = array('id'=>$row['id'],'label'=>$row['label']);
		}
		return $data;
	}
	public function getData($id,$date1,$date2) {
		$dates = $this->dates_range($date1, $date2);
		$params = array('id'=>$id,'date1'=>$date1,'date2'=>$date2);
		$sql = "select * from `mytrend_customized_logs` where id=:id and date>=:date1 and date<=:date2 order by date";
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
