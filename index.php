
<html>
<head>
	<title>Todo List</title>
    <link rel="stylesheet" href="/todo/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>
</head>

<body>
    <div id="app" class="container">
        <h1>Todos</h1>
        <div class="todos">
            <ul class="todolist">
                <li class="todoinput"><input type="text"  v-model="title" type="text"  @keyup.enter="newTodo"  placeholder="What needs to be done?"></li>
                <li  v-for="(todo,index) in todos" :key="todo.id"  class="todoitem">
                    <div class="text" v-if=" !todo.edit" class="data" @dblclick="editOn(todo,index)"  :class="{done : todo.complete}">
                        <input type="checkbox" v-if="!todo.complete" :value="todo.complete" :checked="todo.complete" @change="updateTodo(todo, true)"> {{todo.title}}
                    </div>
                    <div v-else class="input">
                        <input type="text"  v-model="todo.title" @keydown.enter="updateTodo(todo)">
                    </div>
                </li>
            </ul>
            <div class="menu">
                <div class="count">{{counter}} items left</div>
                <div class="options">
                    <button class="btn" :class="{active : state===1}" @click="allTodos">All</button>
                    <button class="btn" :class="{active : state===2}" @click="activeTodo">Active</button>
                    <button class="btn" :class="{active : state===3}" @click="completedTodo">Completed</button>
                </div>
                <div class="clear">
                    <button v-if="state===3 && todos.length !== 0" @click="clearTodo" class="btn" >Clear Completed</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.20.0/axios.min.js" integrity="sha512-quHCp3WbBNkwLfYUMd+KwBAgpVukJu5MncuQaWXgCrfgcxCJAq/fo+oqrRKOj+UKEmyMCG3tb8RB63W+EmrOBg==" crossorigin="anonymous"></script>
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
                count:0
            },
            mounted:function (){
                this.allTodos();
            },
            computed:{
                counter(){
                    let count = 0;
                    this.todos.forEach(todo=>{
                        if(!todo.complete)
                            count++;
                    });
                    if(this.state === 3) count = this.count;
                    this.count = count;
                    return count  ;
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
                    axios.get("http://localhost/todo/includes/process.php?type=active").
                    then(res=>{
                        let tds = res.data.todo;
                        if(res.data.err !== true){
                            console.log(res.data.err);
                            let todos = this.addValue(tds);
                            this.todos = todos;
                        }
                        else{
                            this.todos = [];
                        }
                    });
                },
                completedTodo(){
                    this.state = 3;
                    axios.get("http://localhost/todo/includes/process.php?type=completed").
                    then(res=>{
                        let tds = res.data.todo;
                        if(res.data.err !== true){
                            console.log(res.data.err);
                            let todos = this.addValue(tds);
                            this.todos = todos;
                        }
                        else{
                            this.todos = [];
                        }
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
