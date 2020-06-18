<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\api\backend;

use yii\helpers\Url;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\ApiTester;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserApiKeyEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\UserApiKeyEntity;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\_fixtures\PostFixture;
use DmitriiKoziuk\FakeRestApiModules\Blog\entities\PostEntity;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostNotFoundException;

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

    public function tryToGetAccessWithoutApiToken(ApiTester $I)
    {
        /** @var PostEntity $postEntity */
        $postEntity = $I->grabFixture('posts', 0);
        $url = '/blog/posts/' . $postEntity->id;

        $I->sendGET(Url::to([$url]));
        $I->seeResponseCodeIs(401);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'name' => 'Unauthorized',
            'message' => 'Your request was made with invalid credentials.',
        ]);
    }

    public function tryToCheckResponseStructureForExistPost(ApiTester $I)
    {
        /** @var PostEntity $postEntity */
        $postEntity = $I->grabFixture('posts', 0);
        /** @var UserApiKeyEntity $apiKeyEntity */
        $apiKeyEntity = $I->grabFixture('apiKeys', 0);
        $url = '/blog/posts/' . $postEntity->id;

        $I->seeRecord(PostEntity::class, ['id' => $postEntity->id]);
        $I->amBearerAuthenticated($apiKeyEntity->api_key);
        $I->sendGET(Url::to([$url]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'success' => 'boolean',
            'statusMessage' => 'string',
            'data' => [
                'id' => 'integer',
                'title' => 'string',
                'body' => 'string',
            ],
        ]);
    }

    public function tryToCheckResponseStructureForNotExistPost(ApiTester $I)
    {
        /** @var PostEntity $postEntity */
        $postEntity = $I->grabFixture('posts', 0);
        /** @var UserApiKeyEntity $apiKeyEntity */
        $apiKeyEntity = $I->grabFixture('apiKeys', 0);
        $url = '/blog/posts/' . $postEntity->id;

        $I->seeRecord(PostEntity::class, ['id' => $postEntity->id]);
        $I->amBearerAuthenticated($apiKeyEntity->api_key);
        $I->sendGET(Url::to([$url]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'success' => 'boolean',
            'statusMessage' => 'string',
            'data' => 'array',
        ]);
    }

    public function tryToGetExistPost(ApiTester $I)
    {
        /** @var PostEntity $postEntity */
        $postEntity = $I->grabFixture('posts', 0);
        /** @var UserApiKeyEntity $apiKeyEntity */
        $apiKeyEntity = $I->grabFixture('apiKeys', 0);
        $url = '/blog/posts/' . $postEntity->id;

        $I->seeRecord(PostEntity::class, ['id' => $postEntity->id]);
        $I->amBearerAuthenticated($apiKeyEntity->api_key);
        $I->sendGET(Url::to([$url]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => true,
            'statusMessage' => 'Ok',
            'data' => [
                'id' => $postEntity->id,
                'title' => $postEntity->title,
                'body' => $postEntity->body,
            ],
        ]);
    }

    public function tryToGetNotExistPost(ApiTester $I)
    {
        $notExistPostId = 9999;
        $url = '/blog/posts/' . $notExistPostId;
        $statusMessage = (new PostNotFoundException())->getMessage();
        /** @var UserApiKeyEntity $apiKeyEntity */
        $apiKeyEntity = $I->grabFixture('apiKeys', 0);

        $I->dontSeeRecord(PostEntity::class, ['id' => $notExistPostId]);
        $I->amBearerAuthenticated($apiKeyEntity->api_key);
        $I->sendGET(Url::to([$url]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => false,
            'statusMessage' => $statusMessage,
            'data' => [],
        ]);
    }
}
