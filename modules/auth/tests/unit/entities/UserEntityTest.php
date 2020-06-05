<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\tests\unit\entities;

use DmitriiKoziuk\FakeRestApiModules\Auth\tests\UnitTester;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserApiKeyEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\User;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\UserApiKeyEntity;

class UserEntityTest extends \Codeception\Test\Unit
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

    public function testMethodFindIdentityByAccessTokenReturnApiKeyForExistUser()
    {
        /** @var UserApiKeyEntity $apiKeyEntity */
        $apiKeyEntity = $this->tester->grabFixture('userApiKeys', 0);
        $userEntity = User::findIdentityByAccessToken($apiKeyEntity->api_key);
        $this->assertNotEmpty($userEntity);
        $this->assertInstanceOf(User::class, $userEntity);
        $this->assertEquals($apiKeyEntity->user->id, $userEntity->id);
    }

    public function testMethodFindIdentityByAccessTokenReturnNullForNonExistApiKey(
        string $apiKey = 'nonExistApiKey'
    ) {
        $user = User::findIdentityByAccessToken($apiKey);
        $this->assertEmpty($user);
    }
}
