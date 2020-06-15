<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\tests\api\backend;

use yii\helpers\Url;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\ApiTester;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserApiKeyEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\User;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\UserApiKeyEntity;

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
        $I->seeResponseContains('Hello');
    }

    /**
     * @param ApiTester $I
     */
    public function tryToSignInWithValidData(ApiTester $I)
    {
        /** @var User $user */
        $user = $I->grabFixture('users', 0);
        $I->sendPOST(Url::to('/auth/sign-in'), [
            'username' => $user->username,
            'password' => 'password_0',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        /** @var UserApiKeyEntity $userApiKeyEntity */
        $userApiKeyEntity = $I->grabRecord(UserApiKeyEntity::class, ['user_id' => $user->id]);
        $I->seeResponseContainsJson([
            'success' => true,
            'statusMessage' => 'Ok',
            'data' => [
                'userId' => $user->id,
                'apiKey' => $userApiKeyEntity->api_key,
            ],
        ]);
    }

    public function tryToSignInWithNonExistUser(ApiTester $I)
    {
        $I->sendPOST(Url::to('/auth/sign-in'), [
            'username' => 'nonExistUsername',
            'password' => 'nonExistPassword',
        ]);
        $I->seeResponseContainsJson([
            'success' => false,
            'statusMessage' => "User not found.",
        ]);
    }

    public function tryToSignInWithIncorrectUserPassword(ApiTester $I)
    {
        /** @var User $user */
        $user = $I->grabFixture('users', 0);
        $I->sendPOST(Url::to('/auth/sign-in'), [
            'username' => $user->username,
            'password' => 'incorrectPassword',
        ]);
        $I->seeResponseContainsJson([
            'success' => false,
            'statusMessage' => "Incorrect password.",
        ]);
    }

    public function tryToSignInactiveUser(ApiTester $I)
    {
        $userEntity = $I->grabFixture('users', 'inactive');
        $I->sendPOST(Url::to('/auth/sign-in'), [
            'username' => $userEntity->username,
            'password' => 'password_0',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => false,
            'statusMessage' => "User inactive.",
        ]);
    }

    public function tryToSignDeletedUser(ApiTester $I)
    {
        $userEntity = $I->grabFixture('users', 'deleted');
        $I->sendPOST(Url::to('/auth/sign-in'), [
            'username' => $userEntity->username,
            'password' => 'password_0',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => false,
            'statusMessage' => "User deleted.",
        ]);
    }
}
