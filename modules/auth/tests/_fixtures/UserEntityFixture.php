<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures;

use yii\test\ActiveFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\User;

class UserEntityFixture extends ActiveFixture
{
    public $modelClass = User::class;
}
