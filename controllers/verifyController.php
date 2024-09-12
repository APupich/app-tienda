<?php 
	
	/*< Captura de de la url el valor de la variable token*/
	$vars = ["TOKEN_EMAIL" => explode("=", $_SERVER["REQUEST_URI"])[1]];

	// crea el objeto con la vista
	$tpl = new Kiwi("verify");

	// carga la vista
	$tpl->loadTPL();

	/*< pasa el valor de la variable token a la vista*/
	$tpl->setVarsTPL($vars);

	// imprime en pantalla la página
	$tpl->printTPL();

 ?>