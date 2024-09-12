<?php 

	// se muestra el contenido de SESSION (para debug)
	$usuario = $_SESSION["morphyx"]["user"];

	// crea el objeto con la vista
	$tpl = new Kiwi("perfil");

	// carga la vista
	$tpl->loadTPL();

	// crea el array con variables a reemplazar en la vista
	$vars = ["MSG_REGISTER_ERROR" => "", "NOMBRE" => $usuario->first_name, "APELLIDO" => $usuario->last_name, "AVATAR" => $usuario->avatar];



	// si se presiono el botón del formulario
	if(isset($_POST['btn_guardar'])){

		// quitamos del array de post el boton
		unset($_POST['btn_guardar']);

		// procede a intentar el registro
		$response = $usuario->update($_POST);

		// se actualizo correctamente
		if($response["errno"]==200){

			// redirecciona al panel
			header("Location: panel");
		}

		// Si hubo cualquier error se carga el mensaje de error de la vista
		$vars = ["MSG_REGISTER_ERROR" => $response["error"], "NOMBRE" => $usuario->first_name, "APELLIDO" => $usuario->last_name];
	}


	// se pasan las variables a la vista
	$tpl->setVarsTPL($vars);

	// imprime en pantalla la página
	$tpl->printTPL();


 ?>