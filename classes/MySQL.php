<?php
/*
 * MySQL Class - Used to Connect and Query on MySQL
 * Author : Amardeep Vishwakarma
 */
class MySQL {
		private $conn;
		/*
		 * Execute MySQL Query
		 */
		public function query($sql,$databaseSettings=array()) {
				if(!$databaseSettings) {
						require realpath(dirname(__file__).'/..')."/config/settings.php";
				}
				$conn 	= $this->connect($databaseSettings);
				$res 	= mysql_query($sql);
				return $res;
		}
		/*
		 * Connect to MySQL
		 */
		private function connect($databaseSettings) {
				$host		= $databaseSettings['host'];
				$username	= $databaseSettings['username'];
				$password	= $databaseSettings['password'];
				$port		= $databaseSettings['port'];
				$database	= $databaseSettings['database'];
				$this->conn = mysql_connect("$host:$port",$username,$password) or die(mysql_error());
				if($database) {
						mysql_select_db($database,$this->conn);
				}
				return $this->conn;
		}
		/*
		 * Close MySQL Connection.
		 */
		public function close() {
				mysql_close($this->conn);
		}

}
