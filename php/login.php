<?php
ob_start();
session_start();
require(realpath(dirname(__FILE__)."/../lib/config.php"));
require(realpath(dirname(__FILE__)."/../php/init.php"));

## User y password del POST
$usercn = $_POST['username'];
$password = $_POST['password'];

$conn = libreta_ldap_conectar($usercn,$password);
if (!empty($conn))
{
	session_regenerate_id();
	$_SESSION['sess_user'] = $usercn;
	$_SESSION['sess_user_nombre'] = $usercn;
	$_SESSION['sess_user_pass'] = $password;
	$_SESSION['sess_book'] = $libretas[0]; //Pone la primer libreta de la config
	session_write_close();
	header("Location: ".$BASEDIR."/index.php");
}

else
{ header("Location: ".$BASEDIR."/login.php"); }

libreta_ldap_desconectar($conn);
?>
