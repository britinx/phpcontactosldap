PHPContactosLDAP
================

Un frontend para realizar búsquedas de contactos y administrarlos, en una libreta de direcciones basada en LDAP.

Está escrito en PHP, requiere del módulo php_ldap para funcionar (generalmente se incluye en LAMP/XAMMP). También hace uso del framework Foundation, el cual se encuentra incluído dentro de la misma aplicación, para tener páginas todas lindas y coloridas.

Originalmente diseñado para funcionar en conjunto con un servidor OpenLDAP para una empresa, utiliza un schema bastante sencillo:

* dc=empresa,dc=local:
	* ou=AddressBooks:
		* o=Libreta1:
			* cn=Contacto 1
			* cn=Contacto 2
		* o=Libreta2:
			* cn=Contacto 3
			* cn=Contacto 4
	* ou=Groups:
		* cn=Libreta1
		* cn=Libreta2
	* ou=Users:
		* cn=empleado1
		* cn=empleado2
		* cn=gerente
		* cn=sistemas

Los contactos son de la clase inetOrgPerson, y en todo caso, se puede agregar la clase mozillaAbPerson para tener mejor compatibilidad con Thunderbird.

Si bien en el archivo config.php se pueden editar los parámetros como el DN y las OrganizationalUnits, por el momento el schema no es personalizable por la web (aunque se encuentra bastante documentada y sencilla de editar en el código). En un futuro probablemente se agregue para poder editar la estructura, las clases y filtros de las queryies.
