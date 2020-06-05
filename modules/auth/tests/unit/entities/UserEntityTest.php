<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\tests\unit\entities;

use DmitriiKoziuk\FakeRestApiModules\Auth\entities\User;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\UnitTester;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserApiKeyEntityFixture;

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

    /**
     * @param int    $userId
     * @param string $apiKey
     * @dataProvider apiKeyProvider
     */
    public function testMethodFindIdentityByAccessTokenReturnApiKeyForExistUser(int $userId, string $apiKey)
    {
        $user = User::findIdentityByAccessToken($apiKey);
        $this->assertNotEmpty($user);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($userId, $user->id);
    }

    public function testMethodFindIdentityByAccessTokenReturnNullForNonExistApiKey(
        string $apiKey = 'nonExistApiKey'
    ) {
        $user = User::findIdentityByAccessToken($apiKey);
        $this->assertEmpty($user);
    }

    public function apiKeyProvider()
    {
        $keys = include __DIR__ . '/../../_fixtures/data/auth_user_api_keys.php';
        return [
            $keys[ array_key_first($keys) ],
        ];
    }
}
