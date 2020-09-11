
<html>
<head>
	<title>Todo List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="/todo/css/style.css">
</head>

<body>
    <div id="app">
        <div class="container bg-light p-3">
            <div class="row justify-content-center">
                <div class="col-sm-12 mt-3">
                    <div class="form-group">
                        <input class="form-control" v-model="title" type="text"  @keyup.enter="newTodo" placeholder="What you need to do">
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <ul class="list-group">
                        <li v-for="(todo,index) in todos" :key="todo.id" class="list-group-item">
                            <div v-if=" !todo.edit" class="data" @dblclick="editOn(todo,index)"  :class="{done : todo.complete}">
                                <input class="mr-2" type="checkbox" :value="todo.complete" :checked="todo.complete" @change="updateTodo(todo, true)"> {{todo.title}}
                            </div>
                            <div v-else class="editor">
                                <input class="form-control" type="text" v-model="todo.title" @keydown.enter="updateTodo(todo)">
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row mt-2 justify-content-center">
                <div class="col-sm-3 ">{{counter}} items left</div>
                <div class="col-sm-6 d-flex justify-content-center">
                    <button class="btn btn-sm btn-outline-secondary" :class="{active : state===1}" @click="allTodos">All</button>
                    <button class="btn btn-sm btn-outline-secondary" :class="{active : state===2}" @click="activeTodo">Active</button>
                    <button class="btn btn-sm btn-outline-secondary" :class="{active : state===3}" @click="completedTodo">Completed</button>
                </div>
                <div  class="col-sm-3 d-flex justify-content-end">
                    <button v-if="state===3" @click="clearTodo" class="btn btn-sm btn-outline-secondary">Clear completed</button>
                </div>
            </div>
        </div>
    </div>




    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.20.0/axios.min.js" integrity="sha512-quHCp3WbBNkwLfYUMd+KwBAgpVukJu5MncuQaWXgCrfgcxCJAq/fo+oqrRKOj+UKEmyMCG3tb8RB63W+EmrOBg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qs/6.9.4/qs.min.js" integrity="sha512-BHtomM5XDcUy7tDNcrcX1Eh0RogdWiMdXl3wJcKB3PFekXb3l5aDzymaTher61u6vEZySnoC/SAj2Y/p918Y3w==" crossorigin="anonymous"></script>
    <script>
        let app = new Vue({
            el: '#app',
            data: {
                title: '',
                todos:[],
                num:10,
                editing:false,
                currentIndex:{},
                state:1,
            },
            mounted:function (){
                this.allTodos();
            },
            computed:{
                counter(){
                    return this.todos.length  ;
                }
            },
            methods:{
                allTodos(){
                    axios.get("http://localhost/todo/includes/process.php?type=all").
                    then(res=>{
                        let tds = res.data.todo;
                        let todos = this.addValue(tds);
                        this.todos = todos;
                    });
                    this.state = 1;
                },
                addValue(todos){
                    todos.forEach(todo=>{
                        todo.edit = false;
                        this.strToBool(todo);
                    });
                    return todos;
                },
                strToBool(todo){
                    todo.complete = todo.complete === "1" ? true : false ;
                },
                editOn(todo,i){
                    if(this.editing == true){
                        this.$set(this.todos[this.currentIndex],"edit",false);
                    }
                    if( !todo.complete){
                        this.editing = true;
                        this.currentIndex = i;
                        todo.edit = true;
                    }
                },
                editOff(todo){
                    this.editing = false;
                    todo.edit = false;
                },
                newTodo(){
                    if(this.title !== '') {
                        axios.get("http://localhost/todo/includes/process.php?type=new&title="+this.title)
                        .then(res=>{
                            if(!res.data.err){
                                let todo = res.data.todo;
                                todo.edit = false;
                                this.strToBool(todo);
                                this.todos.push(todo);
                                this.title="";
                            }
                        });
                    }
                },
                updateTodo(todo,completed){
                    if(completed)
                        todo.complete = !todo.complete;
                    axios.get("http://localhost/todo/includes/process.php?type=edit&title="+todo.title+"&status="+todo.complete+"&id="+todo.id )
                        .then(res=>{
                            if(!res.data.err){
                            }
                        });
                    this.editOff(todo);
                },
                checker(value){
                    if(value===true || value ===1 || value === "1"){
                        return true;
                    }
                    return false;
                },
                activeTodo(){
                    this.state = 2;
                },
                completedTodo(){
                    this.state = 3;
                    axios.get("http://localhost/todo/includes/process.php?type=completed").
                    then(res=>{
                        let tds = res.data.todo;
                        let todos = this.addValue(tds);
                        this.todos = todos;
                    });
                },
                clearTodo(){
                    axios.get("http://localhost/todo/includes/process.php?type=del").
                    then(res=>{
                        if( !res.data.err){
                            this.todos = [];
                        }
                    });
                }
            }
        });
    </script>
</body>
</html>
