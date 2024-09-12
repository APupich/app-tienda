<?php 

	// Para evitar que la cache guarde información utilizar DEBUG
	define("RELEASE", 0);
	define("DEBUG", 1);

	/*< Especifica el modo de trabajo de la aplicación*/
	define("MODE", DEBUG);

	// Código único para tokens de la aplicación
	define("WEB_TOKEN", "web token");

	//=== Variables de configuración del proyecto
	$_ENV['PROJECT_NAME'] = "MorphYX";
	$_ENV["PROJECT_DESCRIPTION"] = "Web dedicada a el deporte";
	$_ENV["PROJECT_AUTHOR"] = "Pupich";
	$_ENV["PROJECT_AUTHOR_CONTACT"] = "agustinpupich173@gmail.com";
	$_ENV["PROJECT_URL"] = "http://localhost.com.ar";
	$_ENV["PROJECT_KEYWORDS"] = "deporte";


	$_ENV["PROJECT_WEB_TOKEN"] = WEB_TOKEN;

	/*< Especifica el modo de carga de los CSS y JS */
	$_ENV["PROJECT_MODE"] = MODE ? "?var=".mt_rand(0, 1000) : "";


	//=== Acceso a la base de datos
	$_ENV["HOST"]= "localhost";
	$_ENV["USER"]= "root";
	$_ENV["PASS"]= "";
	$_ENV["DB"]= "test";
	$_ENV["PORT"] = 3306; // puerto de conexión a la DB

	//== Acceso a correo electrónico
	$_ENV["MAILER_REMITENTE"] = '@gmail.com'; // <=== Correo de la app
	$_ENV["MAILER_NOMBRE"] = ''; // <=== Nombre que aparece en el correo
	$_ENV["MAILER_PASSWORD"] = 'aaaa bbbb dddd cccc'; // <=== Token

	$_ENV["MAILER_HOST"] = 'smtp.gmail.com';
	$_ENV["MAILER_PORT"] = '587';
	$_ENV["MAILER_SMTP_AUTH"] = true;
	$_ENV["MAILER_SMTP_SECURE"] = 'tls';


 ?>