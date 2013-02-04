<?php
/*
 * Replication 
 * Author : Amardeep Vishwakarma
 */
class Replication {
		private $dbSettings;
		private $objMySQL;
		/*
		 * Constructor
		 */
		public function __construct($objMySQL) {
				$this->objMySQL     = $objMySQL;
		}
		/*
		 * Get Server Id
		 */
		public function getServerId($dbSettings) {
				$sql = "show variables like 'server_id'";
				$res = $this->objMySQL->query($sql,$dbSettings);
				$row = mysql_fetch_assoc($res);
				return $row['Value'];
		}
		/*
		 * Get List of Slaves.
		 */
		public function getSlaveHosts($dbSettings) {
				$sql = "SHOW SLAVE HOSTS";
				$res = $this->objMySQL->query($sql,$dbSettings);
				$arr = array();
				while($row = mysql_fetch_assoc($res)) {
						$arr[] = $row;
				}
				//$this->objMySQL->close();
				return $arr;
		}
		/*
		 * Get Slave Status
		 */
		public function getSlaveStatus($dbSettings) {
				$sql = "show slave status";
				$res = $this->objMySQL->query($sql,$dbSettings);
				$row = mysql_fetch_assoc($res);
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
