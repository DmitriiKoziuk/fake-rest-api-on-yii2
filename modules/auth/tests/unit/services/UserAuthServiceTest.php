<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\tests\unit\services;

use Yii;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\UnitTester;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserApiKeyEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\forms\UserLoginForm;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\User;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\UserApiKeyEntity;
use DmitriiKoziuk\FakeRestApiModules\Auth\services\UserAuthService;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\UserNotFoundException;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\UserPasswordIncorrectException;

class UserAuthServiceTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function _fixtures()
    {
        return [
            'users' => UserEntityFixture::class,
            'userApiKeys' => UserApiKeyEntityFixture::class,
        ];
    }

    public function testSignInUserWithValidData()
    {
        /** @var User $userEntity */
        $userEntity = $this->tester->grabFixture('users', 0);
        /** @var UserAuthService $userAuthService */
        $userAuthService = Yii::$container->get(UserAuthService::class);
        $userLoginForm = new UserLoginForm([
            'username' => $userEntity->username,
            'password' => 'password_0',
        ]);
        $this->assertTrue($userLoginForm->validate());
        /** @var UserApiKeyEntity $userApiKeyEntity */
        $userApiKey = $userAuthService->signInUser($userLoginForm);
        $userApiKeyEntity = $this->tester->grabRecord(UserApiKeyEntity::class, ['user_id' => $userEntity->id]);
        $this->assertEquals($userApiKeyEntity->api_key, $userApiKey);
    }

    public function testSignInNotExistUser()
    {
        /** @var UserAuthService $userAuthService */
        $userAuthService = Yii::$container->get(UserAuthService::class);
        $userLoginForm = new UserLoginForm([
            'username' => 'nonExistUser',
            'password' => 'nonExistPassword',
        ]);
        $this->assertTrue($userLoginForm->validate());
        $this->expectException(UserNotFoundException::class);
        $userAuthService->signInUser($userLoginForm);
    }

    public function testSignInExistUserWithIncorrectPassword()
    {
        /** @var User $userEntity */
        $userEntity = $this->tester->grabFixture('users', 0);
        /** @var UserAuthService $userAuthService */
        $userAuthService = Yii::$container->get(UserAuthService::class);
        $userLoginForm = new UserLoginForm([
            'username' => $userEntity->username,
            'password' => 'incorrectPassword',
        ]);
        $this->assertTrue($userLoginForm->validate());
        $this->expectException(UserPasswordIncorrectException::class);
        $userAuthService->signInUser($userLoginForm);
    }

    public function testResetUserApiKeyThatAlreadySet()
    {
        /** @var User $userEntity */
        $userEntity = $this->tester->grabFixture('users', 0);
        /** @var UserAuthService $userAuthService */
        $userAuthService = Yii::$container->get(UserAuthService::class);
        $resetUserApiKeyMethod = $this->makeMethodPublic($userAuthService, 'resetUserApiKey');
        $newApiKey = $resetUserApiKeyMethod->invoke($userAuthService, $userEntity);
        $userApiKeyEntity = $this->tester->grabRecord(UserApiKeyEntity::class, ['user_id' => $userEntity->id]);
        $this->assertEquals($userApiKeyEntity->api_key, $newApiKey);
    }

    private function makeMethodPublic(object $object, string $method): \ReflectionMethod
    {
        $reflectedMethod = new \ReflectionMethod($object, $method);
        $reflectedMethod->setAccessible(true);
        return $reflectedMethod;
    }
}
