<?php
/*
 * DBConnection Class - Used to Connect and Query on MySQL
 * Author : Amardeep Vishwakarma
 */
class DBConnection {
    private $conn;
    private $pdo;
    private $parameters;
    private $sQuery;

    public function row($sql,$databaseSettings=array(),$params = null,$fetchmode = PDO::FETCH_ASSOC) {
	if(!$databaseSettings) {
	    require realpath(dirname(__file__).'/..')."/config/settings.php";
	}
	$conn 	= $this->connect($databaseSettings);
	$query = trim($sql);
	$this->Init($query,$params);
	$res = $this->sQuery->fetch($fetchmode);
	$this->sQuery->closeCursor();
	return $res;
    }
    /*
     * Execute MySQL Query
     */
    public function queryPDO($sql,$databaseSettings=array(),$params = null,$fetchmode = PDO::FETCH_ASSOC) {
	if(!$databaseSettings) {
	    require realpath(dirname(__file__).'/..')."/config/settings.php";
	}
	$conn 	= $this->connect($databaseSettings);
	$query = trim($sql);
	$this->Init($query,$params);
	if (stripos($query, 'select') === 0 || stripos($query, 'SELECT') === 0 || stripos($query, 'show') === 0){
	    $res = $this->sQuery->fetchAll($fetchmode);
	    $this->sQuery->closeCursor();			
	    return $res;
	}
	elseif (stripos($query, 'insert') === 0 ||  stripos($query,'update') === 0 ||  stripos($query,'UPDATE') === 0 || stripos($query, 'delete') === 0) {
	    if(stripos(strtolower($query), 'insert') === 0) {
		return $this->pdo->lastInsertId();
	    }
	    $count = $this->sQuery->rowCount();
	    $this->sQuery->closeCursor();
	    return $count;
	}
	else {
	    return NULL;
	}
    }
    public function query($sql,$databaseSettings=array()) {
	if(!$databaseSettings) {
	    require realpath(dirname(__file__).'/..')."/config/settings.php";
	}
	$conn 	= $this->connect($databaseSettings,0);
	$res	= mysql_query($sql);
	return $res;	 
    }
    /*
     * Connect to MySQL
     */
    private function connect($databaseSettings,$pdoConnection=1) {
	$host		= $databaseSettings['host'];
	$username	= $databaseSettings['username'];
	$password	= $databaseSettings['password'];
	$port		= $databaseSettings['port'];
	$database	= $databaseSettings['database'];
	if($pdoConnection==1) {
	    if($database) {
		$dsn = 'mysql:dbname='.$database.';host='.$host.';port='.$port.';';
	    }
	    else {
		$dsn = 'mysql:host='.$host.';port='.$port.';';
	    }
	    try {
		$this->pdo = new PDO($dsn,$username,$password);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	    }
	    catch(Exception $e) {
	    }
	}
	else {
	    $this->conn = mysql_connect("$host:$port",$username,$password) or die(mysql_error());
	    if($database) {
		mysql_select_db($database,$this->conn);
	    }
	    return $this->conn;
	}
    }
    /*
     * Close MySQL Connection.
     */
    public function close($pdoConnection=1) {
	if($pdoConnection==1) {
	    $this->sQuery->closeCursor();
	}
	else {
	    mysql_close($this->conn);
	}
    }
    private function Init($query,$parameters = "") {
	$this->sQuery = $this->pdo->prepare($query);
	$this->bindMore($parameters);
	if(!empty($this->parameters)) {
	    foreach($this->parameters as $param) {
		$parameters = explode("\x7F",$param);
		$this->sQuery->bindParam($parameters[0],$parameters[1]);
	    }
	}
	$this->sQuery->execute();
	$this->parameters = array();
    }
    private function bind($para, $value)
    {
	$this->parameters[sizeof($this->parameters)] = ":" . $para . "\x7F" . $value;
    }
    private function bindMore($parray)
    {
	if(empty($this->parameters) && is_array($parray)) {
	    $columns = array_keys($parray);
	    foreach($columns as $i => &$column) {
		$this->bind($column, $parray[$column]);
	    }
	}
    }
}
