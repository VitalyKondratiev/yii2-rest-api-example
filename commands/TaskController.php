<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use app\models\Calculation;

/**
 * This command calculates split point for array of numbers.
 */
class TaskController extends Controller
{
    /**
    * @var int user id for relation.
    */
    public $uid = null;

    /**
     * This command echoes split point and save equation to database.
     * @return int Exit code
     */
    public function actionIndex($number, array $data)
    {
        $calculation = new Calculation();
        $calculation->user_id = $this->uid;
        $calculation->number = $number;
        $calculation->data = $data;
        if ($calculation->save())
        {
            echo $calculation->split_point_index . "\n";
        }
        else
        {
            $this->stdout("Errors:\n", Console::FG_RED);
            foreach($calculation->errors as $field => $errors)
            {
                $this->stdout("\t" . (implode("\n", $errors) . "\n"), Console::FG_RED);
            }
        }
        return ExitCode::OK;
    }

    public function options($actionID)
    {
        return ['uid'];
    }
}
