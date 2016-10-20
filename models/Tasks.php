<?php

namespace app\models;

use yii\db\ActiveRecord;

class Tasks extends ActiveRecord{
    public function rules(){
        return [
            ['id','integer'],
            ['what','string','length'=>[1,200]],
            ['finish','string','length'=>[0,5]]
        ];
    }

    public static function getAllTasks(){
        $tasks = Tasks::find()->asArray()->all();
        return $tasks;
    }
    public static function addTask($what){
        $task = new Tasks;
        $task->what = $what;
        $task->finish = "no";
        if($task->save()){
            return [
                'id' => $task->attributes['id'],
                'what' => $task->attributes['what'],
                'finish' => $task->attributes['finish']
            ];
        }
    }
    public static function deleteTask($id){
        $task = Tasks::find()->where(['id'=>$id])->one();
        if($task->delete()){
            return $task->attributes['id'];
        }
    }
    public static function changeTask($id,$finish){
        $task = Tasks::find()->where(['id'=>$id])->one();
        if($finish == 'yes'){
            $task->finish = 'no';
        }else{
            $task->finish = 'yes';
        }
        if($task->save()){
            return [
                'id' => $task->attributes['id'],
                'finish' => $task->attributes['finish']
            ];
        }
    }

}