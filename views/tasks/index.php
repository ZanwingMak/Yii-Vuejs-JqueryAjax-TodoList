<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tasks</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .lineThrough{
            text-decoration: line-through;
        }
        a:link{
            text-decoration:none;
        }
        a:visited{
            text-decoration:none;
        }
    </style>
</head>
<body>

<div id="demo" class="container">
    <div id="tasksListContainer">
        <Counter></Counter>
    </div>

    <template id="tasks-template">
        <br/>
        <form class="form-group" @submit="createTask">
            <input type="text" class="form-control" v-model="msg">
            <button type="submit" class="btn btn-success btn-block">添加</button>
        </form>
        <h1>待办事项<span v-show="tasks.length">({{remaining}}/{{ tasks.length }})</span>：</h1>
        <div v-model="tasks"></div>
        <ul>
            <ul class="list-group">
                    <li class="list-group-item" v-for="task in tasks | orderBy 'id' -1">
                        <span v-bind:class="{'lineThrough':task.finish=='yes'}" v-on:click="change(task)"><a href="javascript:;"> {{ task.what }} </a></span><strong><a href="javascript:;" v-on:click="deleteTask(task)">x</a></strong>
                    </li>
            </ul>
        </ul>
    </template>
</div>

<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/vue.js"></script>
<script>
    Vue.component('Counter',{
        template:'#tasks-template',
        data:function(){
            return{
                msg:'',
                tasks:[]
            }
        },
        created:function(){
            var vm = this;
            $.get('?r=tasks/ajaxget',function(data){
                console.log(data);
                vm.tasks = data;
            },'json')
        },
        methods:{
            createTask:function(e){
                e.preventDefault();
                var msg = this.msg.trim();
                if(msg){
                    $.post('?r=tasks/ajaxpost',{what:this.msg,_csrf:'<?php echo \Yii::$app->request->getCsrfToken(); ?>'},function(response){
                        console.log(response);
                        this.tasks.push(response.task);
                        this.msg = '';
                    }.bind(this),'json');
                }
            },
            change:function(task) {
                $.post('?r=tasks/ajaxchange',{id:task.id,finish:task.finish,_csrf:'<?php echo \Yii::$app->request->getCsrfToken(); ?>'},function(response){
                    console.log(response);
                    task.finish = response.task.finish;
                }.bind(this),'json');
            },
            deleteTask:function (task) {
                $.post('?r=tasks/ajaxdelete',{id:task.id,_csrf:'<?php echo \Yii::$app->request->getCsrfToken(); ?>'},function(response){
                    console.log(response);
                    this.tasks.$remove(task);
                }.bind(this));
            }
        },
        computed:{
            remaining:function () {
                return this.tasks.filter(function (task) {
                    return (task.finish=='no');
                }).length;
            }
        }
    });

    var tasksListContainer = new Vue({
        el:'#tasksListContainer'
    });

    var demo = new Vue({
        el:'#demo'
    });
</script>
</body>
</html>