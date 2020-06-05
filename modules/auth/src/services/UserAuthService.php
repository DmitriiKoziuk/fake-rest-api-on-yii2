<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\services;

use Yii;
use yii\base\Exception;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\User;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\UserApiKeyEntity;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\UserPasswordIncorrectException;
use DmitriiKoziuk\FakeRestApiModules\Auth\forms\UserLoginForm;
use DmitriiKoziuk\FakeRestApiModules\Auth\forms\UserSignUpForm;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\UserNotFoundException;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\UserApiKeySaveException;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\UserAlreadyExistException;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\forms\UserSignUpFormNotValidException;

class UserAuthService
{
    /**
     * Return api key if user exist
     * @param UserLoginForm $userLoginForm
     * @return string
     * @throws Exception
     * @throws UserApiKeySaveException
     * @throws UserNotFoundException
     * @throws UserPasswordIncorrectException
     * @throws \Throwable
     */
    public function signInUser(UserLoginForm $userLoginForm): string
    {
        $user = User::findByUsername($userLoginForm->username);
        if (empty($user)) {
            throw new UserNotFoundException();
        }
        if (! $user->validatePassword($userLoginForm->password)) {
            throw new UserPasswordIncorrectException();
        }
        return $this->resetUserApiKey($user);
    }

    /**
     * @param UserSignUpForm $userSignUpForm
     * @return array ['userId' =>, 'apiKey' =>]
     * @throws UserSignUpFormNotValidException
     * @throws UserAlreadyExistException
     */
    public function signUpUser(UserSignUpForm $userSignUpForm): array
    {
        if (! $userSignUpForm->validate()) {
            throw new UserSignUpFormNotValidException();
        }
        $userEntity = User::findByUsername($userSignUpForm->username);
        if (! empty($userEntity)) {
            throw new UserAlreadyExistException();
        }
        $userEntity = $this->saveUser($userSignUpForm);
        $userApiKey = $this->resetUserApiKey($userEntity);
        return [
            'userId' => $userEntity->id,
            'apiKey' => $userApiKey,
        ];
    }

    /**
     * @param User $user
     * @return string
     * @throws Exception
     * @throws UserApiKeySaveException|\Throwable
     */
    private function resetUserApiKey(User $user): string
    {
        if (! empty($user->apiKeyEntity)) {
            $user->apiKeyEntity->delete();
        }
        $userApiKeyEntity = new UserApiKeyEntity([
            'user_id' => $user->id,
            'api_key' => Yii::$app->security->generateRandomString(),
        ]);
        if (! $userApiKeyEntity->save()) {
            throw new UserApiKeySaveException('Cannot save User Api Key');
        }
        return $userApiKeyEntity->api_key;
    }

    private function saveUser(UserSignUpForm $userSignUpForm): User
    {
        $user = new User();
        $user->username = $userSignUpForm->username;
        $user->email = $userSignUpForm->email;
        $user->setPassword($userSignUpForm->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->save();
        return $user;
    }
}
