<?php

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\api\backend;

use DmitriiKoziuk\FakeRestApiModules\Blog\tests\ApiTester;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserApiKeyEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\_fixtures\PostFixture;

class PostViewCest
{
    public function _fixtures()
    {
        return [
            'users' => UserEntityFixture::class,
            'apiKeys' => UserApiKeyEntityFixture::class,
            'posts' => PostFixture::class,
        ];
    }
}
