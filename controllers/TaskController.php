<?php

namespace app\controllers;

use yii\rest\Controller;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\filters\auth\HttpBasicAuth;
use app\models\Calculation;

class TaskController extends Controller
{
    protected function verbs(){
        return [
            'index' => ['POST'],
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON
            ]
        ];
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'auth' => '\app\models\User::findByCredentials',
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $user_id = \Yii::$app->user->getId();
        $number = \Yii::$app->request->post('number') ?: null;
        $data = \Yii::$app->request->post('data') ?: [];
        $split_point_index = $this->makeCalculation($user_id, $number, $data, $split_point_index);
        return $split_point_index;
    }

    private function makeCalculation($user_id, $number, $data, $split_point_index)
    {
        $calculation = new Calculation();
        $calculation->user_id = $user_id;
        $calculation->number = $number;
        $calculation->data = $data;
        return $calculation->save() ? $calculation->split_point_index : null;
    }
}