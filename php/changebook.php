<?php
session_start();
require(realpath(dirname(__FILE__)."/lib/config.php"));
require(realpath(dirname(__FILE__)."/php/init.php"));

isset($_SESSION['sess_user']) or die(header('Location: '.$BASEDIR.'/login.php'));
if($_SESSION["sess_user_nombre"] == $CN_ADMIN)
{ $admin = TRUE; }

// Esto es horrible, es para cambiar la libreta
// cambiando una variable de $_SESSION.
if(isset($_GET["book"])) {
	for ($x=0; $x<count($libretas)-1; $x++) {
		if($_GET["book"] == $libretas[$x])
		{ $_SESSION["sess_book"] = $libretas[$x]; $bookchanged = TRUE;}
	}
}
// Por lo menos ahora verifica dinámicamente si existe.

if(!isset($_GET["book"]) || $bookchanged != TRUE)
{ die(libreta_ldap_error("Libreta de direcciones invalida")); }

header('Location: '.$_SERVER['HTTP_REFERER']);
?>