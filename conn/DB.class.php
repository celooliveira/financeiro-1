<?php

class DB{
	public function conectar(){

		// Localhost
		$host = "localhost";
		$user = "user_finan";
		$pass = "123456";
		$database = "financeiro";

		// Hostgator
		/*$host = "localhost";
		$user = "atite621_finan";
		$pass = "finan123";
		$database = "atite621_financeiro";*/

		// Conexao com o banco
		$mysqli = new mysqli($host, $user, $pass, $database);

		// Definindo o charset como utf8 para evitar problemas com acentuação
		$mysqli->query("SET NAMES utf8");

		// Check conexao
		if (mysqli_connect_errno()) {
			die('Erro ao conectar-se ao banco de dados: ' . mysqli_connect_error());
		    exit();
		}

		return $mysqli;

	} // fim function conectar
} // fim class DB

// Conexao com o banco de dados
$conectar = new DB;
$conectar = $conectar->conectar();

?>