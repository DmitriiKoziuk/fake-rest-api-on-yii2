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
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\UserInactiveException;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\UserDeletedException;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\forms\UserSignUpFormNotValidException;

class UserAuthService
{
    /**
     * Return api key if user exist
     * @param UserLoginForm $userLoginForm
     * @return array ['userId' =>, 'apiKey' =>]
     * @throws Exception
     * @throws UserApiKeySaveException
     * @throws UserNotFoundException
     * @throws UserInactiveException
     * @throws UserDeletedException
     * @throws UserPasswordIncorrectException
     * @throws \Throwable
     */
    public function signInUser(UserLoginForm $userLoginForm): array
    {
        /** @var User $user */
        $user = User::find()
            ->where(['username' => $userLoginForm->username])
            ->limit(1)
            ->one();
        if (empty($user)) {
            throw new UserNotFoundException();
        }
        if (User::STATUS_INACTIVE == $user->status) {
            throw new UserInactiveException();
        }
        if (User::STATUS_DELETED == $user->status) {
            throw new UserDeletedException();
        }
        if (! $user->validatePassword($userLoginForm->password)) {
            throw new UserPasswordIncorrectException();
        }
        return [
            'userId' => $user->id,
            'apiKey' => $this->resetUserApiKey($user),
        ];
    }

    /**
     * @param UserSignUpForm $userSignUpForm
     * @return array ['userId' =>, 'apiKey' =>]
     * @throws UserSignUpFormNotValidException
     * @throws UserAlreadyExistException
     * @throws \Throwable
     */
    public function signUpUser(UserSignUpForm $userSignUpForm): array
    {
        $this->validateUserSignUpForm($userSignUpForm);
        $this->checkIsUserAlreadyExist($userSignUpForm);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $userEntity = $this->createUser($userSignUpForm);
            $userApiKey = $this->resetUserApiKey($userEntity);
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
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

    private function createUser(UserSignUpForm $userSignUpForm): User
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

    private function validateUserSignUpForm(UserSignUpForm $userSignUpForm): void
    {
        if (! $userSignUpForm->validate()) {
            throw new UserSignUpFormNotValidException($userSignUpForm->getErrors());
        }
    }

    private function checkIsUserAlreadyExist(UserSignUpForm $userSignUpForm): void
    {
        $userEntity = User::find()
            ->where(['username' => $userSignUpForm->username])
            ->limit(1)
            ->one();
        if (! empty($userEntity)) {
            throw new UserAlreadyExistException();
        }
    }
}
