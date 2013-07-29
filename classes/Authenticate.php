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
	public function checkPassword($role,$password) {
		$PASSWORD = $this->getPassword($role);
		if(md5(trim($password)) == trim($PASSWORD)) {
			$uniqId 			= uniqid();
			$_SESSION['uniqid'] = $uniqId;
			$_SESSION['role'] 	= $role;
			setcookie("uniqid",$uniqId);
			return true;
		}
		return false;
	}
	/*
	 * Get the Role
	 */
	public function getRole() {
		return $_SESSION['role'];
	}
	/*
	 * Get the Password which was set during the Installation
	 */
	private function getPassword($role) {
		$params = array('role'=>$role);
		$result = $this->objDBConnection->row("SELECT `password` FROM `mytrend_users` WHERE `role`=:role",array(),$params);
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
}
?>
