<?php
/*
 * Authentication Class
 * Author : Amardeep Vishwakarma
 */
class Authenticate {
    private $objMySQL;
    /*
     * Constructor
     */
    public function __construct($objMySQL) {
	$this->objMySQL = $objMySQL;
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
	$res = $this->objMySQL->query("SELECT `password` FROM `mytrend_users` WHERE `role`='$role'");
	$row = mysql_fetch_assoc($res);
	return $row['password'];
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
