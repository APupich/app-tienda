<?php 

	// crea el objeto con la vista
	$tpl = new Kiwi("listado");

	// carga la vista
	$tpl->loadTPL();

	$vars = ["PROJECT_SECTION" => "Listado"];

	$tpl->setVarsTPL($vars);

	// imprime en la vista en la página
	$tpl->printTPL();

 ?>