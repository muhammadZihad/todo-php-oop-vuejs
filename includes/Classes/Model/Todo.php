<?php

	namespace Todo\Classes\Model;
	use Todo\Database\Database;
	use Todo\Classes\Struct\Struct;
	class Todo extends Database {
		protected $db;

		public function __construct(){
			$this->db = $this->connect();
		}

		public function all(){
			$sql = 'select * from todos';
			try{
				$todos = $this->db->prepare($sql);
				$todos->execute();
				return $todos->fetchALl();
			}catch(PDOException $e){
			echo $e->getMessage();
			}
		}

		public function create(Struct $data){
			$sql = "insert into todos(title) values(?)";
			try{
				$todo = $this->db->prepare($sql);
				return $todo->execute([$data->title]);
			}catch(PDOException $e){
				echo $e->getMessage();
			}
			header("location:","/");
		}

		public function update(Struct $data){
			$sql = "update todos set title = ? , complete = ? where id = ? ";
			try{
				if($data->complete=='true'){
					$data->complete = 1 ;
				}
				else{
					$data->complete = 0 ;
				}
				$todo = $this->db->prepare($sql);
				return $todo->execute([$data->title, $data->complete, $data->id]);
			}catch(\PDOException $e){
				return $e->getMessage();
			}
		}

		public function lastTodo(){
			$sql = 'select * from todos order by id desc limit 1';
			try{
				$todos = $this->db->prepare($sql);
				$todos->execute();
				return $todos->fetch();
			}catch(PDOException $e){
				echo $e->getMessage();
			}
		}

		public function completed(){
			$sql = 'select * from todos where complete = 1';
			try{
				$todos = $this->db->prepare($sql);
				$todos->execute();
				return $todos->fetchALl();
			}catch(PDOException $e){
				echo $e->getMessage();
			}
		}

		public function delete(){
			$sql = 'delete from todos where complete = 1';
			try{
				$todos = $this->db->prepare($sql);
				$todos->execute();
				return true;
			}catch(PDOException $e){
				echo $e->getMessage();
			}
		}
	}