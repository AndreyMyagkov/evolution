<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if(!$modx->hasPermission('save_password')) {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = $_POST['id'];
$pass1 = $_POST['pass1'];
$pass2 = $_POST['pass2'];

if($pass1!=$pass2){
	$modx->webAlertAndQuit("Passwords don't match!");
}

if(strlen($pass1)<6){
	$modx->webAlertAndQuit("Password is too short. Please specify a password of at least 6 characters.");
}

    $pass1 = $modx->getPhpCompat()->htmlspecialchars($pass1, ENT_NOQUOTES);
    $uid = $modx->getLoginUserID('mgr');
    $password = $modx->getPasswordHash()->HashPassword($pass1);
    $user = EvolutionCMS\Models\User::find($uid);
    $user->update(['password' => $password]);

	// invoke OnManagerChangePassword event
	$modx->invokeEvent('OnManagerChangePassword', array (
		'userid' => $uid,
		'username' => $_SESSION['mgrShortname'],
		'userpassword' => $pass1
	));

$header="Location: index.php?a=2";
header($header);
