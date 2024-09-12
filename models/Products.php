<?php

	/**
	* @file Products.php
	* @brief Declaraciones de la clase Products para la conexión con la base de datos.
	* @author Matias Leonardo Baez
	* @date 2024
	* @contact elmattprofe@gmail.com
	*/

	// incluye la libreria para conectar con la db
	include_once 'DBAbstract.php';
	
	/*< Se crea la clase Products que hereda de DBAbstract */
	class Products extends DBAbstract{


		public $list = [];


		/**
		 * 
		 * @brief constructor de la clase
		 * Limpia la lista de productos
		 * 
		 * */
		function __construct(){
			$this->list = [];
		}


		/**
		 * 
		 * @brief Agrega un producto a la lista
		 * @param string $request_method Espera POST
		 * @param array $request info del producto
		 * @return array resultado de agregar
		 * 
		 * */
		function add($request_method, $request){

			if($request_method!="POST"){
				return ["errno" => 410, "error" => "Metodo invalido"];
			}

			$this->list[] = $request;

			return ["errno" => 200, "error" => "Producto agregado", "productlist" => $this->list];
		}

		/**
		 * 
		 * @brief Lista de productos
		 * @return array lista de productos
		 * 
		 * */
		function getAll(){
			return $this->list;
		}
	}


 ?>