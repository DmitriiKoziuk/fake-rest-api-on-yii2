<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\api\backend;

use yii\helpers\Url;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\ApiTester;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserApiKeyEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\UserApiKeyEntity;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\_fixtures\PostFixture;

class PostGetCest
{
    public function _fixtures()
    {
        return [
            'users' => UserEntityFixture::class,
            'apiKeys' => UserApiKeyEntityFixture::class,
            'posts' => PostFixture::class,
        ];
    }

    public function tryToGetAccessWithoutApiToken(ApiTester $I)
    {
        $I->sendGET(Url::to(['/blog/posts']));
        $I->seeResponseCodeIs(401);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'name' => 'Unauthorized',
            'message' => 'Your request was made with invalid credentials.',
        ]);
    }

    public function tryGetPostsResourceWithBearerToken(ApiTester $I)
    {
        /** @var UserApiKeyEntity $apiKeyEntity */
        $apiKeyEntity = $I->grabFixture('apiKeys', 0);

        $I->amBearerAuthenticated($apiKeyEntity->api_key);
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
                'totalItems' => 'string',
                'results' => 'array',
            ],
        ]);
    }
}
