<?php

namespace App;

class Connection {

	public static function getDb() {
		try {
			//mysql:host= ipbanco
			//dbname= nome_banco
			//root
			//'' *senha vazia*
			$conn = new \PDO(
				"mysql:host=localhost;dbname=sigr;charset=utf8",
				"root",
				"" 
			);

			return $conn;

		} catch (\PDOException $e) {
			//.. tratar de alguma forma ..//
			echo 'Ops, ocorreu um erro com o banco de dados!';
		}
	}
}

?>