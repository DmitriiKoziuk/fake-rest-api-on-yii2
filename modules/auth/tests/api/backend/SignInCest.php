<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\tests\api\backend;

use yii\helpers\Url;
use Codeception\Lib\Console\Output;
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

    /**
     * @param ApiTester $I
     * @depends tryToCheckIsSignInResourceWork
     */
    public function tryToSignInWithValidData(ApiTester $I)
    {
        [$username, $password, $apiKey] = $this->getValidUserData();
        $output = new Output([]);
        $output->writeln("\nPost username '{$username}' and password '{$password}'");
        $I->sendPOST(Url::to('/auth/sign-in'), [
            'username' => $username,
            'password' => $password,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['apiKey' => $apiKey]);
    }

    private function getValidUserData()
    {
        return ['bayer.hudson', 'password_0', 'tS6v0GwInVgc28QHpIiOgG4pwKaE3ikJ'];
    }
}
