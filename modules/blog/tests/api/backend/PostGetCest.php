<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\api\backend;

use DmitriiKoziuk\FakeRestApiModules\Auth\entities\UserApiKeyEntity;
use yii\helpers\Url;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\ApiTester;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserApiKeyEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\_fixtures\PostFixture;

class PostGetCest
{
    public function _fixtures()
    {
        return [
            'users' => UserEntityFixture::class,
            'apiKeys' => UserApiKeyEntityFixture::class,
            'postFixture' => PostFixture::class,
        ];
    }

    public function tryGetPostsResourceWithoutBearerToken(ApiTester $I)
    {
        $I->sendGET(Url::to(['/blog/posts']));
        $I->seeResponseCodeIs(401);
    }

    public function tryGetPostsResourceWithBearerToken(ApiTester $I)
    {
        $bearerToken = $this->getBearerToken();
        $I->amBearerAuthenticated($bearerToken);
        $I->sendGET(Url::to(['/blog/posts']));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function tryCheckIsResponseDataStructureCorrect(ApiTester $I)
    {
        /** @var UserApiKeyEntity $apiKeyEntity */
        $apiKeyEntity = $I->grabFixture('apiKeys', 0);
        $bearerToken = $apiKeyEntity->api_key;
        $I->amBearerAuthenticated($bearerToken);
        $I->sendGET(Url::to(['/blog/posts']));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'success' => 'boolean',
            'statusMessage' => 'string',
            'data' => [
                'page' => 'integer',
                'resultsPerPage' => 'integer',
                'totalItems' => 'integer',
                'results' => 'array',
            ],
        ]);
    }

    private function getBearerToken()
    {
        $userApiKeys = include __DIR__ . '/../../../../auth/tests/_fixtures/data/auth_user_api_keys.php';
        return $userApiKeys[ array_key_first($userApiKeys) ]['api_key'];
    }
}
