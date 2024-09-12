<?php 

	/**
	* @file User.php
	* @brief Declaraciones de la clase User para la conexión con la base de datos.
	* @author Matias Leonardo Baez
	* @date 2024
	* @contact elmattprofe@gmail.com
	*/

	// incluye la libreria para conectar con la db
	include_once 'DBAbstract.php';

	/*< incluye la clase Mailer.php para enviar correo electrónico*/
	include_once 'Mailer.php';

	// se crea la clase User que hereda de DBAbstract
	class User extends DBAbstract{

		private $nameOfFields = array();

		/**
		 * 
		 * @brief Es el constructor de la clase User
		 * 
		 * Al momento de instanciar User se llama al padre para que ejecute su constructor
		 * 
		 * */
		function __construct(){
		
			// quiero salir de la clase actual e invocar al constructor
			parent::__construct();

			/**< Obtiene la estructura de la tabla */
			$result = $this->query('DESCRIBE users');

			foreach ($result as $key => $row) {
				$buff =$row["Field"];
				
				/**< Almacena los nombres de los campos*/
				$this->nameOfFields[] = $buff;

				/**< Autocarga de atributos a la clase */
				$this->$buff=NULL;
			}
			

		}

		/**
		 * 
		 * Hace soft delete del registro
		 * @return bool siempre verdadero
		 * 
		 * */
		function leaveOut(){

			$id = $this->id;
			$fecha_hora = date("Y-m-d H:i:s");

			$ssql = "UPDATE users SET delete_at='$fecha_hora' WHERE id=$id";

			$this->query($ssql);

			return true;
		}

		/**
		 * 
		 * Finaliza la sesión
		 * @return bool true
		 * 
		 * */
		function logout(){
			return true;
		}

		/**
		 * 
		 * Intenta loguear al usuario mediante email y contraseña
		 * @param array $form indexado de forma asociativa
		 * @return array que posee códigos de error especiales
		 * 
		 * */
		function login($form){

			/*< recupera el method http*/
			$request_method = $_SERVER["REQUEST_METHOD"];

			/* si el method es invalido*/
			if($request_method!="GET"){
				return ["errno" => 410, "error" => "Metodo invalido"];
			}

			/*< recupera el email del formulario*/
			$email = $form["txt_email"];

			/*< consultamos si existe el email*/
			$result = $this->query("CALL `login`('$email')");

			// el email no existe
			if(count($result)==0){
				return ["error" => "Email no registrado", "errno" => 404];
			}

			/*< seleccionamos solo la primer fila de la matriz*/
			$result = $result[0];

			// si el email existe y la contraseña es valida
			if($result["password"]==md5($form["txt_pass"].$_ENV["PROJECT_NAME"])){

				/**< autocarga de valores en los atributos de la clase */
				foreach ($this->nameOfFields as $key => $value) {
					$this->$value = $result[$value];
				}

				/*< carga la clase en la sesión*/
				$_SESSION["morphyx"]['user'] = $this;

				/*< usuario valido*/
				return ["error" => "Acceso valido", "errno" => 200];
			}

			// email existe pero la contraseña invalida
			return ["error" => "Error de contraseña", "errno" => 405];

		}

		/**
		 * 
		 * Agrega un nuevo usuario si no existe el correo electronico en la tabla users
		 * @param array $form es un arreglo assoc con los datos del formulario
		 * @return array que posee códigos de error especiales 
		 * 
		 * */
		function register($form){
			$request_method = $_SERVER["REQUEST_METHOD"];

			/* si el method es invalido*/
			if($request_method!="POST"){
				return ["errno" => 410, "error" => "Metodo invalido"];
			}
			/*< recupera el email*/
			$email = $form["txt_email"];

			/*< consulta si el email ya esta en la tabla de usuarios*/
			$result = $this->query("SELECT * FROM users WHERE email = '$email'");
			if(count($result)!=0){
				return ["error" => "Email registrado", "errno" => 405];
			}

				/*< encripta la contraseña*/
				$pass = md5($form["txt_pass"].$_ENV["PROJECT_NAME"]);

				/*< se crea el token único para validar el correo electrónico*/
				$token = md5($_ENV['PROJECT_WEB_TOKEN'].$email);

				/*< agrega el nuevo usuario y deja en pendiente de validar su email*/
				$ssql = "INSERT INTO users (token,email, password) VALUES ('$token','$email','$pass')";

				/*< ejecuta la consulta*/
				$result = $this->query($ssql);

				/*< se recupera el id del nuevo usuario*/
				$this->id = $this->db->insert_id;
				/*< aviso de registro exitoso*/
				return ["error" => "Usuario registrado", "errno" => 200];
		}


		/**
		 * 
		 * Actualiza los datos del usuario con los datos de un formulario
		 * @param array $form es un arregle asociativo con los datos a actualizar
		 * @return array arreglo con el código de error y descripción
		 * 
		 * */
		function update($form){
			$nombre = $form["txt_first_name"];
			$apellido = $form["txt_last_name"];
			$id = $this->id;


			$this->first_name = $nombre;
			$this->last_name = $apellido;

			$ssql = "UPDATE users SET first_name='$nombre', last_name='$apellido' WHERE id=$id";

			$result = $this->query($ssql);

			return ["error" => "Se actualizo correctamente", "errno" => 200];
		}

		/**
		 * 
		 * Cantidad de usuarios registrados
		 * @return int cantidad de usuarios registrados
		 * 
		 * */
		function getCantUsers(){

			$result = $this->query("SELECT * FROM users");

			return $this->db->affected_rows;
		}


		/**
		 * 
		 * @brief Retorna un listado limitado
		 * @param string $request_method espera a GET
		 * @param array $request [inicio][cantidad]
		 * @return array lista con los datos de los usuarios 
		 * 
		 * */
		function getAllUsers($request){

			$request_method = $_SERVER["REQUEST_METHOD"];

			/*< Es el método correcto en HTTP?*/
			if($request_method!="GET"){
				return ["errno" => 410, "error" => "Metodo invalido"];
			}

			/*< Solo un usuario logueado puede ver el listado */
			if(!isset($_SESSION["morphyx"])){
				return ["errno" => 411, "error" => "Para usar este método debe estar logueado"];
			}

			/*

			if(!isset($_SESSION["morphyx"]['user_level'])){

				if($_SESSION["morphyx"]['user_level']!='admin'){
				return ["errno" => 412, "error" => "Solo el 	administrador puede utilizar el metodo"];
				}
			}

			*/


			$inicio = 0;

			if(isset($request["inicio"])){
				$inicio = $request["inicio"];
			}

			if(!isset($request["cantidad"])){
				return ["errno" => 404, "error" => "falta cantidad por GET"];
			}

			$cantidad = $request["cantidad"];

			$result = $this->query("SELECT * FROM users LIMIT $inicio, $cantidad");

			return $result;
		}


	}

 ?>