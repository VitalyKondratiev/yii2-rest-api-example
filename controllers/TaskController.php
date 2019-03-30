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
        $split_point_index = self::getSplitPointIndex($number, $data);
        $this->saveCalculation($user_id, $number, $data, $split_point_index);
        return $split_point_index;
    }

    private function saveCalculation($user_id, $number, $data, $split_point_index)
    {
        $calculation = new Calculation();
        $calculation->user_id = $user_id;
        $calculation->number = $number;
        $calculation->data = $data;
        $calculation->split_point_index = $split_point_index;
        return $calculation->save();
    }

    /**
     * Returns index before split point
     */
    private static function getSplitPointIndex($number, $data)
    {
        for ($split_point = 1; $split_point < count($data);  $split_point++)
        {
            $parts = [];
            $parts['first'] = array_slice($data, 0, $split_point);
            if (!in_array($number, $parts['first']))
            {
                continue;
            }
            $first_count = (array_count_values($parts['first'])[$number]);
            $parts['second'] = array_slice($data, $split_point, count($data));
            $second_count = (!in_array($number, $parts['second'])) ?
                count($parts['second']) :
                count($parts['second']) - (array_count_values($parts['second'])[$number]);
            if ($first_count == $second_count)
            {
                return $split_point;
                break;
            }
        }
        return -1;
    }
}