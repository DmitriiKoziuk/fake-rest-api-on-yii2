<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\tests\api\backend;

use yii\helpers\Url;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\ApiTester;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserApiKeyEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\User;

class SignUpCest
{
    public function _fixtures()
    {
        return [
            'users' => UserEntityFixture::class,
            'userApiKeys' => UserApiKeyEntityFixture::class,
        ];
    }

    public function tryToCheckIsSignInResourceWork(ApiTester $I)
    {
        $I->sendGET(Url::to(['/auth/sign-up']));
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('Hello, SignUp');
    }

    public function tryToSignUpWithoutUserData(ApiTester $I)
    {
        $I->sendPOST(Url::to(['/auth/sign-up']));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => false,
            'statusMessage' => "User sign up form not valid.",
            'data' => [
                'username' => [
                    'Username cannot be blank.',
                ],
                'email' => [
                    'Email cannot be blank.',
                ],
                'password' => [
                    'Password cannot be blank.',
                ],
            ],
        ]);
    }

    public function tryToSignUpWithAlreadyExistUserData(ApiTester $I)
    {
        /** @var User $userEntity */
        $userEntity = $I->grabFixture('users', 0);
        $I->sendPOST(Url::to(['/auth/sign-up']), [
            'username' => $userEntity->username,
            'email' => 'a@a.com',
            'password' => 'password',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => false,
            'statusMessage' => "User already exist.",
        ]);
    }
}
