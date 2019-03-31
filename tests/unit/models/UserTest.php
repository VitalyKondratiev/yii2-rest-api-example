<?php

namespace tests\unit\models;

use app\models\User;

class UserTest extends \Codeception\Test\Unit
{
    public function testFindUserById()
    {
        expect_that($user = User::findIdentity(1));
        expect($user->username)->equals('admin');

        expect_not(User::findIdentity(999));
    }

    public function testFindUserByAccessToken()
    {
        expect_that($user = User::findIdentityByAccessToken('YWRtaW46JDJ5JDEzJEN3dU1FRnJCOGZpMms3cFVBUlVESXVTYnZocDdUb2NDcjRJczA2cmxtSDEydGRmQzd0OGVD'));
        expect($user->username)->equals('admin');

        expect_not(User::findIdentityByAccessToken('non-existing'));        
    }

    public function testFindUserByUsername()
    {
        expect_that($user = User::findByUsername('admin'));
        expect_not(User::findByUsername('not-admin'));
    }

    /**
     * @depends testFindUserByUsername
     */
    public function testValidateUser($user)
    {
        $right_auth_key = User::findIdentity(1)->auth_key;
        $user = User::findByUsername('admin');
        expect_that($user->validateAuthKey($right_auth_key));
        expect_not($user->validateAuthKey('wrong_auth_key'));

        expect_that($user->validatePassword('admin'));
        expect_not($user->validatePassword('123456'));        
    }

}
