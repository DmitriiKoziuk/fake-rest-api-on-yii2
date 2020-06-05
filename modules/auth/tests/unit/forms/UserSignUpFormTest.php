<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\tests\unit\forms;

use Codeception\Test\Unit;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\UnitTester;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserApiKeyEntityFixture;

class UserSignUpFormTest extends Unit
{
    protected UnitTester $tester;

    public function _fixtures()
    {
        return [
            'users' => UserEntityFixture::class,
            'userApiKeys' => UserApiKeyEntityFixture::class,
        ];
    }

    public function validDataProvider()
    {
        return [
            [
                'username' => 'a',
                'email' => 'a',
                'password' => 'a'
            ],
        ];
    }

    public function notValidDataProvider()
    {
        return [
            [
                'username' => '',
                'email' => '',
                'password' => ''
            ],
        ];
    }
}
