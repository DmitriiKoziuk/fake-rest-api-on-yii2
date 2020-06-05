<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\tests\unit\services;

use Yii;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\UnitTester;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserApiKeyEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\forms\UserLoginForm;
use DmitriiKoziuk\FakeRestApiModules\Auth\forms\UserSignUpForm;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\User;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\UserApiKeyEntity;
use DmitriiKoziuk\FakeRestApiModules\Auth\services\UserAuthService;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\UserNotFoundException;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\UserPasswordIncorrectException;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\UserAlreadyExistException;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\forms\UserSignUpFormNotValidException;

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

    public function testMethodSignInUserReturnApiKeyForExistUser()
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

    public function testMethodSignInUserThrowErrorForNonExistUser()
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

    public function testMethodSignInUserThrowErrorForUserWithIncorrectPassword()
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

    public function testMethodResetUserApiKeySuccessfulResetAlreadyExistApiKey()
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

    public function testMethodSignUpUserThrowErrorForNotValidSignUpUserForm()
    {
        /** @var UserAuthService $userAuthService */
        $userAuthService = Yii::$container->get(UserAuthService::class);
        $userSignUpForm = new UserSignUpForm();
        $this->expectException(UserSignUpFormNotValidException::class);
        $userAuthService->signUpUser($userSignUpForm);
    }

    public function testMethodSignUpUserThrowErrorForAlreadyExistUser()
    {
        /** @var UserAuthService $userAuthService */
        $userAuthService = Yii::$container->get(UserAuthService::class);
        /** @var User $userEntity */
        $userEntity = $this->tester->grabFixture('users', 0);
        $userSignUpForm = new UserSignUpForm([
            'username' => $userEntity->username,
            'email' => $userEntity->email,
            'password' => 'password',
        ]);
        $this->expectException(UserAlreadyExistException::class);
        $userAuthService->signUpUser($userSignUpForm);
    }

    public function testMethodCreateUserWork()
    {
        /** @var UserAuthService $userAuthService */
        $userAuthService = Yii::$container->get(UserAuthService::class);
        $method = $this->makeMethodPublic($userAuthService, 'createUser');
        $userSignUpForm = new UserSignUpForm([
            'username' => 'nonExistUserName',
            'email' => 'nonExistEmail@g.com',
            'password' => 'nonExistPassword',
        ]);
        $this->tester->dontSeeRecord(User::class, ['username' => $userSignUpForm->username]);
        $this->assertInstanceOf(User::class, $method->invoke($userAuthService, $userSignUpForm));
        $this->tester->seeRecord(User::class, ['username' => $userSignUpForm->username]);
    }


    public function testMethodSignUpUserSuccessfulCreateNewUserWithApiKey()
    {
        /** @var UserAuthService $userAuthService */
        $userAuthService = Yii::$container->get(UserAuthService::class);
        $userSignUpForm = new UserSignUpForm([
            'username' => 'nonExistUserName',
            'email' => 'nonExistEmail@g.com',
            'password' => 'nonExistPassword',
        ]);
        $this->tester->dontSeeRecord(User::class, ['username' => $userSignUpForm->username]);
        $createdUserData = $userAuthService->signUpUser($userSignUpForm);
        $this->assertIsArray($createdUserData);
        $this->assertArrayHasKey('userId', $createdUserData);
        $this->assertArrayHasKey('apiKey', $createdUserData);
        $this->tester->seeRecord(User::class, ['id' => $createdUserData['userId']]);
        $this->tester->seeRecord(UserApiKeyEntity::class, [
            'user_id' => $createdUserData['userId'],
            'api_key' => $createdUserData['apiKey'],
        ]);
    }

    private function makeMethodPublic(object $object, string $method): \ReflectionMethod
    {
        $reflectedMethod = new \ReflectionMethod($object, $method);
        $reflectedMethod->setAccessible(true);
        return $reflectedMethod;
    }
}
