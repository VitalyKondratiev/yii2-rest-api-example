<?php

namespace app\modules\api;

class ApiModule extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        \Yii::configure($this, require __DIR__ . '/config.php');
    }
}