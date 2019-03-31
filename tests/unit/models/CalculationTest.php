<?php

namespace tests\unit\models;

use app\models\Calculation;

class CalculationTest extends \Codeception\Test\Unit
{
    public function testCalculateSplitPointPositive()
    {
        expect(Calculation::calculateSplitPoint(5, [5, 5, 1, 7, 2, 3, 5]))->equals(4);
        expect(Calculation::calculateSplitPoint(5, [5, 1, 1, 7, 2, 3, 5]))->equals(5);
    }

    public function testCalculateSplitPointNegative()
    {
        expect(Calculation::calculateSplitPoint(5, [6, 6, 6, 6, 6]))->equals(-1);
        expect(Calculation::calculateSplitPoint(7, [7, 7, 7]))->equals(-1);
    }

    public function testCalculationSave()
    {
        $calculation = new Calculation();
        $calculation->number = 5;
        $calculation->data = [5, 5, 1, 7, 2, 3, 5];
        expect_not($calculation->id);
        expect_that($calculation->save());
        expect($calculation->split_point_index)->equals(4);
    }
}
