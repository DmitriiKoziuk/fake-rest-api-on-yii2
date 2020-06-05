<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\tests\api\backend;

use yii\helpers\Url;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\ApiTester;

class SignUpCest
{
    public function tryToCheckIsSignInResourceWork(ApiTester $I)
    {
        $I->sendGET(Url::to(['/auth/sign-up']));
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('Hello, SignUp');
    }
}
