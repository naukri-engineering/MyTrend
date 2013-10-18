<?php
/*
 * Authentication Class
 * Author : Amardeep Vishwakarma
 */
class Authenticate {
	private $objDBConnection;
	/*
	 * Constructor
	 */
	public function __construct($objDBConnection) {
		$this->objDBConnection = $objDBConnection;
	}
	/*
	 * Checks for the Passwords
	 */
	public function checkPassword($username,$password) {
		$PASSWORD = $this->getPassword($username);
		if(md5(trim($password)) == trim($PASSWORD)) {
			$uniqId 			= uniqid();
			$_SESSION['uniqid'] = $uniqId;
			$_SESSION['username'] 	= $username;
			setcookie("uniqid",$uniqId);
			return true;
		}
		return false;
	}
	/*
	 * Get the Username
	 */
	public function getUsername() {
		return $_SESSION['username'];
	}
	/*
	 * Get the Password which was set during the Installation
	 */
	private function getPassword($username) {
		$params = array('username'=>$username);
		$result = $this->objDBConnection->row("SELECT `password` FROM `mytrend_users` WHERE `role`=:username",array(),$params);
		return $result['password'];
	}
	/*
	 * Logout from MyTrend
	 */
	public function logout() {
		unset($_COOKIE['uniqid']);
		unset($_SESSION['uniqid']);
	}
	/*
	 * Checks for the Authentication
	 */
	public function authenticate() {
		if(!$_COOKIE['uniqid'] || !$_SESSION['uniqid'])
			return false;
		if($_COOKIE['uniqid'] == $_SESSION['uniqid'])
			return true;
		return false;
	}
	public function getOtherUsers() {
		$result = $this->objDBConnection->queryPDO("SELECT `role` as username,`password` FROM `mytrend_users` WHERE `role` not in('superadmin','admin')",array(),array());
		return $result;		
	}
}
?>
