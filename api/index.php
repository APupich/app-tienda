<?php

	/**
	* @file index.php
	* @brief API Restful para el proyecto
	* @author Matias Leonardo Baez
	* @date 2024
	* @contact elmattprofe@gmail.com
	*/

	/*< inicia o continua la sesión*/
	session_start();

	/*< La respuesta será un JSON*/
	header("Content-Type: application/json");

	/*< Se incluyen las variables de entorno*/
	include_once '../env.php';

	/*< Se incluyen las librerias para el envio de correo electrónico*/
	include '../lib/mp-mailer/Mailer/src/PHPMailer.php';
	include '../lib/mp-mailer/Mailer/src/SMTP.php';
	include '../lib/mp-mailer/Mailer/src/Exception.php';

	// captura el nombre del request method
	$request_method = $_SERVER["REQUEST_METHOD"];

	// captura los datos desde el vector de method correspondiente
	switch ($request_method) {
		case 'DELETE':
		case 'GET':
				$request = $_GET;
			break;

		case 'POST':
		case 'PUT':
				/*< las variables que se envian por método PUT viajan en el body */
				/*< se captura la petición y se pasan las variables al vector $_PUT */
				$_PUT = json_decode(file_get_contents('php://input'),true);
				$request = $_PUT;
			break;
	}
	
	/*< obtiene todo lo que esta delante de /api/ */
	$url = str_replace("/app-tienda/api/","",$_SERVER["REDIRECT_URL"]);

	/*< averigua si al final de la url hay una barra y la quita */
	if(substr($url, -1) == "/"){
		$url = substr($url, 0, -1);
	}

	/*< separa los elementos que hay en la url */
	$url_detone = explode("/", $url);

	/*< en la primer posición está el nombre del modelo */
	$name_of_model = $url_detone[0];

	// Compatibiliza la peticion del modelo con el nombre de la clase
	$modelo = ucfirst(strtolower($name_of_model));

	/*< Si no existe el modelo entonces  */
	if(!file_exists('../models/'.$modelo.'.php')){
		echo json_encode(["load_errno" => 404 , "load_error" => "Modelo inexistente"]);
		exit();	
	}

	// incluye el modelo
	include_once '../models/'.$modelo.'.php';

	/*< si no existe el segundo elemento entonces falta el método */
	if(!isset($url_detone[1])){
		echo json_encode(["load_errno" => 405 , "load_error" => "Falta especificar metodo"]);
		exit();	
	}

	/*< en la segunda posición está el método */
	$name_of_method = $url_detone[1];

	/*< instancia la clase solicitada en el modelo*/
	$object = new $modelo;

	/*< Si no existe el método dentro de la clase */
	if(!method_exists($object, $name_of_method)){
		echo json_encode(["load_errno" => 406 , "load_error" => "Metodo no existente dentro de la clase"]);
		exit();	
	}

	
	/*< genera el código único para que no se cachee la respuesta de la API*/
	$finger_print = md5($_ENV["PROJECT_WEB_TOKEN"].date("Y-m-d+H:i:s+").mt_rand(0, 5000));

	/*< almacena la respuesta del método dentro de la posición list */
	$response = ["fingerprint"=> $finger_print,"load_errno" => 200 , "load_error" => "", "list" => $object->$name_of_method($request)];

	/*< convierte la respuesta en un JSON */
	echo json_encode($response);