<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\tests\api\backend;

use yii\helpers\Url;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\ApiTester;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserApiKeyEntityFixture;

class SignInCest
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
        $I->sendGet(Url::to('/auth/sign-in'));
        $I->seeResponseCodeIs(200);
    }
}
