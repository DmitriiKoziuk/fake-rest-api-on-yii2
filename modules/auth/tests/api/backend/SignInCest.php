<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\tests\api\backend;

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
}
