<?php
/*
 * MyTrend Class - Deals with the data stored in MyTrend Database
 * Author : Amardeep Vishwakarma
 */
class MyTrendUsers {
    private $objDBConnection;
    /*
     * Constructor
     */
    public function __construct($objDBConnection) {
	$this->objDBConnection = $objDBConnection;
    }
    public function getUsers() {
	$sql = "SELECT `role` FROM `mytrend_users` WHERE `role` not in('superadmin','admin')";
	$result = $this->objDBConnection->queryPDO($sql,array(),array());
	$users = array();
	foreach($result as $row) {
	    $users[] = $row['role'];
	}	
	return $users;
    }
    public function getAssginedGroups($ROLE) {
	$sql = "SELECT group_name from `mytrend_users_groups` where username=:ROLE";
	$result2 = $this->objDBConnection->queryPDO($sql,array(),array('ROLE'=>$ROLE));
	$assignedGroups = array();
	foreach($result2 as $res) {
	    $assignedGroups[] = $res['group_name'];
	}
	return $assignedGroups;
    }
    public function deleteUser($username) {
	$sql = "DELETE from `mytrend_users` WHERE `role` not in('superadmin','admin') and `role`=:username";
	$params = array('username'=>$username);
	$this->objDBConnection->queryPDO($sql,array(),$params);
	$this->deleteAssignedGroups($username);
    }
    public function deleteAssignedGroups($username) {
	$sql = "DELETE from `mytrend_users_groups` where username=:username";
	$this->objDBConnection->queryPDO($sql,array(),array('username'=>$username));
    }
    public function addUser($username,$password) {
	$sql = "INSERT ignore into `mytrend_users`(`role`,`password`) values(:username,:password)";
	$this->objDBConnection->queryPDO($sql,array(),array('username'=>$username,'password'=>md5($password)));
    }
    public function updatePassword($username,$password) {
	$sql = "UPDATE `mytrend_users` set `password`=:password WHERE `role`=:username";
	$this->objDBConnection->queryPDO($sql,array(),array('username'=>$username,'password'=>md5($password)));
    }
    public function assignServerGroups($username,$userServerGroup) {
	$this->deleteAssignedGroups($username);
	foreach($userServerGroup as $serverGroup) {
	    $sql = "INSERT into `mytrend_users_groups`(`username`,`group_name`) values(:username,:group_name)";
		$this->objDBConnection->queryPDO($sql,array(),array('username'=>$username,'group_name'=>$serverGroup));
	}
    }
}
