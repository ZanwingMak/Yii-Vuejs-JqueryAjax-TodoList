<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Tasks;

class TasksController extends Controller{
    public function actionIndex(){
        $tasks_list = Tasks::getAllTasks();
        $tasks_list_json = json_encode($tasks_list);
        $data['tasks_list_json'] = $tasks_list_json ;
        return $this->renderPartial('index',$data);
    }
    public function actionAjaxget(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            if($tasks_list = Tasks::getAllTasks()){
                $tasks_list_json = json_encode($tasks_list);
                return $tasks_list_json;
            }
        }
    }
    public function actionAjaxpost(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $post_what = $request->post('what');
            if($task = Tasks::addTask($post_what)){
                return json_encode([
                    'status' => 'success',
                    'task' => $task
                ]);
            }
        }
    }
    public function actionAjaxdelete(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $post_id = $request->post('id');
            if($id = Tasks::deleteTask($post_id)){
                return 'delete '.$id.' success.';
            }
        }
    }
    public function actionAjaxchange(){
        $request = \Yii::$app->request;
        if($request->isAjax){
            $post_id = $request->post('id');
            $post_finish = $request->post('finish');
            if($task = Tasks::changeTask($post_id,$post_finish)){
                return json_encode([
                    'status' => 'success',
                    'task' => $task
                ]);
            }
        }
    }
}