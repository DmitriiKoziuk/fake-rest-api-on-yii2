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

    public function signUpUser(UserSignUpForm $userSignUpForm): string
    {
        return '';
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
}
