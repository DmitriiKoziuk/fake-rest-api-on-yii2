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

    public function tryToSignUpWithoutUserData(ApiTester $I)
    {
        $I->sendPOST(Url::to(['/auth/sign-up']));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => false,
            'statusMessage' => "User sign up form not valid.",
        ]);
    }
}
