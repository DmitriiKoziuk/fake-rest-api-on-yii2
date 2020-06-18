<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\api\backend;

use yii\helpers\Url;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\ApiTester;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserApiKeyEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\UserApiKeyEntity;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\_fixtures\PostFixture;
use DmitriiKoziuk\FakeRestApiModules\Blog\entities\Post;

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
        /** @var Post $postEntity */
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

    public function tryToGetPost(ApiTester $I)
    {
        /** @var Post $postEntity */
        $postEntity = $I->grabFixture('posts', 0);
        /** @var UserApiKeyEntity $apiKeyEntity */
        $apiKeyEntity = $I->grabFixture('apiKeys', 0);
        $url = '/blog/posts/' . $postEntity->id;

        $I->amBearerAuthenticated($apiKeyEntity->api_key);
        $I->sendGET(Url::to([$url]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'id' => $postEntity->id,
            'title' => $postEntity->title,
            'body' => $postEntity->body,
        ]);
    }
}
