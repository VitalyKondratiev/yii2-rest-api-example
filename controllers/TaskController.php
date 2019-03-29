<?php

namespace app\controllers;

use yii\rest\Controller;
use yii\filters\ContentNegotiator;
use yii\web\Response;

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
        return $behaviors;
    }

    public function actionIndex()
    {
        $number = \Yii::$app->request->post('number') ?: null;
        $data = \Yii::$app->request->post('data') ?: [];
        $split_point_index = self::getSplitPointIndex($number, $data);
        return $split_point_index;
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