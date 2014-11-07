<?php

/* ARCHIVO DE CONFIGURACION */
$VERSION = "0.9.1b";

$SERVIDOR = "127.0.0.1";	// IP o hostname del servidor LDAP
$PUERTO = 389;			// Puerto del servidor LDAP
$BASEDIR = "/contactos";	// Directorio donde esta instalada la app

$BASEDN = "dc=empresa,dc=local";// BaseDN del LDAP
$SEP = ",";			// Separador de parametros del query

$CN_ADMIN = "Manager";		// CN del administrador
$CN_PASSWORD = "PASSWORD";	// Password del administrador

$OU_LIBRETA = "ou=AddressBooks";// Unidad organizacional de las libretas
$OU_USUARIOS = "ou=Users";	// Unidad organizacional de los usuarios

$FOOTER = "";
$CREDITS = "2014 - Leandro Britez - Contactos - Version: ".$VERSION;

/* PERMISOS DE EDICION */
// $EDITAR_USER habilita o deshabilita las páginas de edición
// de contactos para los usuarios normales.
$EDITAR_USER = [
	"Empresa" => FALSE,
	"Proveedores" => TRUE,
	"Mayoristas" => TRUE
];
// $EDITAR_ADMIN habilita o deshabilita las páginas de edición
// de contactos para los usuarios administradores (Manager).
$EDITAR_ADMIN = [
	"Empresa" => TRUE,
	"Proveedores" => TRUE,
	"Mayoristas" => TRUE
];

/* FIN DE LA CONFIGURACION */
?>
