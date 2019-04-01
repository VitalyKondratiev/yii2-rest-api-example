<?php

namespace app\modules\api\controllers;

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
            'list' => ['GET'],
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
        $calculation = $this->getCalculation($user_id, $number, $data);
        if (!$calculation->save()) {
            $errors = implode(array_map('implode', $calculation->errors)); // combine from array to one string
            $errors = strtolower(rtrim($errors, '.')); // trim last dot in the string and lowercase it
            $errors = str_replace('.' , ', ', $errors); // trim last dot in the string and lowercase it
            throw new \yii\web\HttpException(418, $errors);
        }
        else {
            return [
                'result' => $calculation->split_point_index,
            ];
        }
    }

    public function actionList()
    {
        $user_id = \Yii::$app->user->getId();
        $calculations = Calculation::find()
            ->select(['number', 'data', 'split_point_index', 'created_at'])
            ->where(['user_id' => $user_id])
            ->orderBy(['created_at' => SORT_ASC])
            ->all();
        return [
            'count' => count($calculations),
            'list' => $calculations,
        ];
    }

    private function getCalculation($user_id, $number, $data)
    {
        $calculation = new Calculation();
        $calculation->user_id = $user_id;
        $calculation->number = $number;
        $calculation->data = $data;
        return $calculation;
    }
}