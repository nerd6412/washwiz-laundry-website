<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();

if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}

if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}

if($action == "save_supply"){
	$save = $crud->save_supply();
	if($save)
		echo $save;
}
if($action == "delete_supply"){
	$save = $crud->delete_supply();
	if($save)
		echo $save;
}
if($action == "save_inv"){
	$save = $crud->save_inv();
	if($save)
		echo $save;
}
if($action == "delete_inv"){
	$save = $crud->delete_inv();
	if($save)
		echo $save;
}




