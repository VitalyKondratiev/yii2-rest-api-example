<?php

class ApiModuleCest
{
    public function _before(\ApiTester $I)
    {
        $I->amHttpAuthenticated('admin', 'admin');
    }

    public function successTaskRequest(\ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/task', json_encode([
            'number' => 5,
            'data' => [5,5,1,7,2,3,5],
        ]));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.result');
    }

    public function successTaskListRequest(\ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/task/list');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseJsonMatchesJsonPath('$.count');
        $I->seeResponseJsonMatchesJsonPath('$.list');
    }

    public function failRequestTaskUnauthorized(\ApiTester $I)
    {
        $I->amHttpAuthenticated('hacker', 'wrong_password');
        $I->sendPOST('/task', json_encode([
            'number' => 5,
            'data' => [5,5,1,7,2,3,5],
        ]));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED);
    }

    public function failRequestTaskWrongJSON(\ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/task', json_encode([
            'number' => 5,
        ]));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::I_AM_A_TEAPOT);
    }
}