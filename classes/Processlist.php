<?php
/*
 * Class to get processlist data.
 * Author : Amardeep Vishwakarma
 */
class Processlist {
		private $dbSettings;
		private $objMySQL;
		/*
		 * Constructor (Input : MySQL Object and Database Connection Setting(
		 */
		public function __construct($objMySQL,$dbSettings) {
				$this->objMySQL     = $objMySQL;
				$this->dbSettings   = $dbSettings;
		}
		/*
		 * Get current running processlist.
		 */
		public function getProcessList() {
				$res = $this->objMySQL->query('show processlist',$this->dbSettings);
				$arr = array();
				while($row = mysql_fetch_assoc($res)) {
						$arr[] = $row;
				}
				return $arr;
		}
}
?>
