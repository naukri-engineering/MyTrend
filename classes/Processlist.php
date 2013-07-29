<?php
/*
 * Class to get processlist data.
 * Author : Amardeep Vishwakarma
 */
class Processlist {
	private $dbSettings;
	private $objDBConnection;
	/*
	 * Constructor (Input : MySQL Object and Database Connection Setting(
	 */
	public function __construct($objDBConnection,$dbSettings) {
		$this->objDBConnection     = $objDBConnection;
		$this->dbSettings   = $dbSettings;
	}
	/*
	 * Get current running processlist.
	 */
	public function getProcessList() {
		$result = array();
		$result = $this->objDBConnection->queryPDO('show processlist',$this->dbSettings);
		return $result;
	}
}
?>
