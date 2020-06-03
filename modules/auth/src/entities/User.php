<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\entities;

class User extends \common\models\User
{
    public static function findIdentityByAccessToken($token, $type = null): ?\common\models\User
    {
        /** @var UserApiKeyEntity $userApiKeyEntity */
        $userApiKeyEntity = UserApiKeyEntity::find()->where([
            'api_key' => $token,
        ])->one();
        if (! empty($userApiKeyEntity)) {
            return $userApiKeyEntity->user;
        }
        return null;
    }
}
