<?php 

	
	
	// Desactiva el reporte de errores de mysqli
	mysqli_report(MYSQLI_REPORT_OFF);

	/**
	 * 
	 * Clase para heredar en los modelos de tabla que conecta a la base de datos
	 * 
	 * */
	class DBAbstract{

		public $db;

		/**
		 * 
		 * Crea el objeto
		 * 
		 * */
		function __construct(){
			$this->connect();
		}

		/**
		 * 
		 * Realiza la conexión a la base de datos
		 * 
		 * */
		function connect(){
				// se genera el objeto y se silencia los warning
			$this->db = @new mysqli($_ENV['HOST'], $_ENV['USER'], $_ENV['PASS'], $_ENV['DB'], $_ENV['PORT']);
			// si hubo un error de conexión a la base de datos
			if($this->db->connect_errno){

				// muestra el error
				echo "Hubo un error de conexión: (".$this->db->connect_errno.") <b>".$this->db->connect_error."</b>";
				exit();
			}
		}

		/**
		 * 
		 * Retorna el resultad de la query en forma de array assoc
		 * sirve para query DML SELECT CALL DESCRIBE
		 * 
		 * @param string $sql query en formato SQL
		 * @return array es un array assoc
		 * 
		 * */
		function query($sql){			

			$this->connect();

			//var_dump($sql);
			//exit();

			$result = $this->db->query($sql);

			// en caso de error en la query
			if($this->db->errno){
				echo "Hubo un error en la query: ".$this->db->error;
				exit();
			}

			// extrae la primer palabra de la query
			$first_word = strstr($sql, " ",true);

			switch ($first_word) {
				// si las querys son del tipo que retornan una matriz
				case 'CALL':
				case 'DESCRIBE':
				case 'SELECT':
					return $result->fetch_all(MYSQLI_ASSOC);
					break;
				
				default:
					return true;
					break;
			}

			
		}

	}


 ?>