<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\tests\unit\forms;

use Codeception\Test\Unit;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\UnitTester;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserApiKeyEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\forms\UserSignUpForm;

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

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @dataProvider validDataProvider
     */
    public function testUserSignUpFormWithValidData(string $username, string $email, string $password)
    {
        $userSignUpForm = new UserSignUpForm([
            'username' => $username,
            'email' => $email,
            'password' => $password,
        ]);
        $this->assertTrue($userSignUpForm->validate());
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @dataProvider notValidDataProvider
     */
    public function testUserSignUpFormWithNotValidData(string $username, string $email, string $password)
    {
        $userSignUpForm = new UserSignUpForm([
            'username' => $username,
            'email' => $email,
            'password' => $password,
        ]);
        $this->assertFalse($userSignUpForm->validate());
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
            'All fields not valid' => [
                'username' => '',
                'email' => '',
                'password' => '',
            ],
        ];
    }
}
