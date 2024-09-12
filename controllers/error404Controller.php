<?php 
	
	// crea el objeto con la vista
	$tpl = new Kiwi("error404");

	// carga la vista
	$tpl->loadTPL();

	// imprime en la página la vista
	$tpl->printTPL();

 ?>