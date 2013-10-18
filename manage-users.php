<?php
/*
 * Manage Users.
 * Author : Amardeep Vishwakarma
 */
require_once "include.php";
if(!$AUTH || $ROLE != 'superadmin') {
    $smarty->display('index.html');
    die;
}
$objDBConnection = new DBConnection();
$objMyTrendUsers = new MyTrendUsers($objDBConnection);

if($_GET['action'] == 'delete') {
    $username = $_GET['username'];
    $objMyTrendUsers->deleteUser($username);
    $smarty->assign('success','User deleted successfully');
}
else if($_POST['action'] == 'adduser') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $objMyTrendUsers->addUser($username,$password);
    $smarty->assign('success','User added successfully');
}
else if($_POST['action'] == 'changePassword') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $objMyTrendUsers->updatePassword($username,$password);
    $smarty->assign('success','Password updated successfully');
}
else if($_POST['action'] == 'assignServerGroup') {
    $username = $_POST['username'];
    $userServerGroup = $_POST['userServerGroup'];
    $objMyTrendUsers->assignServerGroups($username,$userServerGroup);
    $smarty->assign('success','Server(s) assigned successfully');
}
$users = $objMyTrendUsers->getUsers();
$allUsers = array();
foreach($users as $user) {
    $allUsers[] = array('user'=>$user,'server_group'=>$objMyTrendUsers->getAssginedGroups($user));
}
if(!$allUsers) {
    $smarty->assign('warning','Currently you do not have any user added.');
}
$smarty->assign('users',$allUsers);
$smarty->assign('PageSelected',10);
$smarty->display('manage-users.html');
?>
