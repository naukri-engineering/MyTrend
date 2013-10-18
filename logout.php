<?php
/*
 * Logout from MyTrend.
 * Author : Amardeep Vishwakarma
 */
require_once "include.php";
$authObj->logout();
$smarty->assign('otherUsers',$authObj->getOtherUsers());
$smarty->assign("AUTH",0);
$smarty->assign("success","You've signed out of MyTrend.");
$smarty->display('index.html');
?>
