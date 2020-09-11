<?php
	require_once "../vendor/autoload.php";
	use Todo\Classes\Controller\TodoController;
	use Todo\Classes\Struct\Struct;


	$type = "";
	$return = array("err"=>false);

	if(isset($_GET["type"])){
		$type = $_GET["type"];
		if($type == "all"){
			$return["todo"] = allTodo();
			echo json_encode($return);
		}
		elseif ($type == "new"){
//			echo $_POST["title"];
			$todos = new TodoController;
			$req = new Struct;
			$req->title = $_GET["title"];
			if($todos->addTodo($req)){
				$return["todo"] = $todos->newTodo();
				echo json_encode($return);
			}
		}
		elseif ($type == "edit"){
			$todos = new TodoController;
			$req = new Struct;
			$req->id = $_GET["id"];
			$req->title = $_GET["title"];
			$req->complete = $_GET["status"];
//			if($todos->updateTodo($req));
				echo json_encode($todos->updateTodo($req));
		}
		elseif($type == "completed"){
			$todos = new TodoController;
//			echo $todos->allCompleted();
			if($return["todo"] = $todos->allCompleted()){
				echo json_encode($return);
			}
		}
		elseif($type == "del"){
			$todos = new TodoController;
			if($todos->clear()){
				echo json_encode($return);
			}
		}
		else{
			echo "invalid";
		}
	}

	function allTodo(){
		$todos = new TodoController;
		return $todos->allTodos();
	}