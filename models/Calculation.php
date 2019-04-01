<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use klisl\behaviors\JsonBehavior;
use app\models\User;

/**
 * This is the model class for table "calculation".
 *
 * @property int $id
 * @property int $user_id
 * @property int $number
 * @property array $data
 * @property int $split_point_index
 * @property integer $created_at
 * @property integer $updated_at
 */
class Calculation extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'calculation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data', 'number'], 'required'],
            ['number', 'integer'],
            ['data', 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => JsonBehavior::class,
                'property' => 'data',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }

    public function beforeSave($insert)
    {
        $this->split_point_index = self::calculateSplitPoint($this->number, $this->data);
        return parent::beforeSave($insert);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function calculateSplitPoint($number, $data)
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
