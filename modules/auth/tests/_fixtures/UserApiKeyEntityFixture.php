<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures;

use yii\test\ActiveFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\UserApiKeyEntity;

class UserApiKeyEntityFixture extends ActiveFixture
{
    public $modelClass = UserApiKeyEntity::class;
}
