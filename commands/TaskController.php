<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Calculation;

/**
 * This command calculates split point for array of numbers.
 */
class TaskController extends Controller
{
    /**
    * @var int number for split rule.
    */
    public $number = null;
    /**
    * @var array array for splitting.
    */
    public $data = [];
    /**
    * @var int user id for relation.
    */
    public $uid = null;

    /**
     * This command echoes split point and save equation to database.
     * @return int Exit code
     */
    public function actionIndex()
    {
        $calculation = new Calculation();
        $calculation->user_id = $this->uid;
        $calculation->number = $this->number;
        $calculation->data = $this->data;
        $calculation->save();
        echo $calculation->split_point_index . "\n";
        return ExitCode::OK;
    }

    public function options($actionID)
    {
        return ['number', 'data', 'uid'];
    }
}
