<?php
	namespace Todo\Database;
	use PDO;

	class Database {
		private $host="localhost";
		private $user="root";
		private $pass="";
		private $dbname="todoapp";

		public function connect (){
			$link = 'mysql:hosts='.$this->host.';dbname='.$this->dbname;
			try{
				$pdo = new PDO($link,$this->user,$this->pass);
				$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
				return $pdo;
			}catch(PDOException $e){
				echo $e->getMessage();
			}
		}
	}
