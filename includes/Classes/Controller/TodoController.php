<?php

	namespace Todo\Classes\Controller;

	use Todo\Classes\Model\Todo;
	use Todo\Classes\Struct\Struct;
	class TodoController {

		public function allTodos(){
			$todo = new Todo;
			return $todo->all();
		}

		public function addTodo(Struct $req){
			$todo = new Todo;
			return $todo->create($req);
		}

		public function newTodo(){
			$todo = new Todo;
			return $todo->lastTodo();
		}

		public function updateTodo(Struct $req){
			$todo = new Todo;
			return $todo->update($req);
		}

		public function allCompleted(){
			$todo = new Todo;
			return $todo->completed();
		}

		public function clear(){
			$todo = new Todo;
			return $todo->delete();
		}
	}
