<?php
/*
 * Replication 
 * Author : Amardeep Vishwakarma
 */
class Replication {
	private $dbSettings;
	private $objDBConnection;
	/*
	 * Constructor
	 */
	public function __construct($objDBConnection) {
		$this->objDBConnection     = $objDBConnection;
	}
	/*
	 * Get Server Id
	 */
	public function getServerId($dbSettings) {
		$sql = "show variables like 'server_id'";
		$res = $this->objDBConnection->row($sql,$dbSettings);
		return $res['Value'];
	}
	/*
	 * Get List of Slaves.
	 */
	public function getSlaveHosts($dbSettings) {
		$sql = "show slave hosts";
		$res = $this->objDBConnection->query($sql,$dbSettings);
		$arr = array();
		while($row = mysql_fetch_assoc($res)) {
		    $arr[] = $row;
		}
		return $arr;
	}
	/*
	 * Get Slave Status
	 */
	public function getSlaveStatus($dbSettings) {
		$sql = "show slave status";
		$row = $this->objDBConnection->row($sql,$dbSettings);
		if(!$row)
			return 'Yes';
		if($row['Slave_IO_Running'] == 'Yes' && $row['Slave_SQL_Running'] == 'Yes') 
			return 'Yes';
		return 'No';
	}
	/*
	 * Get list of Color Codes
	 */
	public function getColorCodes() {
		return array('#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C');
	}
}
?>
