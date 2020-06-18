<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\api\backend;

use yii\helpers\Url;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\ApiTester;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserApiKeyEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\UserApiKeyEntity;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\_fixtures\PostFixture;
use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostUpdateForm;
use DmitriiKoziuk\FakeRestApiModules\Blog\entities\PostEntity;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostNotFoundException;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostUpdateFormNotValidException;

class PostUpdateCest
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
        $I->sendPUT(Url::to(['/blog/posts/1']));
        $I->seeResponseCodeIs(401);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'name' => 'Unauthorized',
            'message' => 'Your request was made with invalid credentials.',
        ]);
    }

    public function tryUpdatePostWithoutAnyData(ApiTester $I)
    {
        /** @var PostEntity $postEntity */
        $postEntity = $I->grabFixture('posts', 0);
        /** @var UserApiKeyEntity $apiKeyEntity */
        $apiKeyEntity = $I->grabFixture('apiKeys', 0);
        $url = "/blog/posts/{$postEntity->id}";

        $I->amBearerAuthenticated($apiKeyEntity->api_key);
        $I->sendPUT(Url::to([$url]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => false,
            'statusMessage' => (new PostUpdateFormNotValidException([]))->getMessage(),
            'data' => [
                'title' => [
                    'Title cannot be blank.'
                ],
                'body' => [
                    'Body cannot be blank.'
                ],
            ],
        ]);
    }

    public function tryUpdateNotExistPost(ApiTester $I)
    {
        /** @var UserApiKeyEntity $apiKeyEntity */
        $apiKeyEntity = $I->grabFixture('apiKeys', 0);
        $postUpdateForm = new PostUpdateForm([
            'id' => 9999,
            'title' => 'New post title.',
            'body' => 'New post body.',
        ]);
        $url = "/blog/posts/{$postUpdateForm->id}";

        $I->assertTrue($postUpdateForm->validate());
        $I->dontSeeRecord(PostEntity::class, ['id' => $postUpdateForm->id]);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amBearerAuthenticated($apiKeyEntity->api_key);
        $I->sendPUT(Url::to([$url]), [
            'title' => $postUpdateForm->title,
            'body' => $postUpdateForm->body,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => false,
            'statusMessage' => (new PostNotFoundException())->getMessage(),
            'data' => [],
        ]);
    }

    public function tryUpdatePostByValidData(ApiTester $I)
    {
        /** @var UserApiKeyEntity $apiKeyEntity */
        $apiKeyEntity = $I->grabFixture('apiKeys', 0);
        /** @var PostEntity $postEntity */
        $postEntity = $I->grabFixture('posts', 9);
        $postUpdateForm = new PostUpdateForm([
            'id' => $postEntity->id,
            'title' => 'New post title.',
            'body' => 'New post body.',
        ]);
        $url = "/blog/posts/{$postUpdateForm->id}";

        $I->assertTrue($postUpdateForm->validate());
        $I->seeRecord(PostEntity::class, ['id' => $postUpdateForm->id]);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amBearerAuthenticated($apiKeyEntity->api_key);
        $I->sendPUT(Url::to([$url]), [
            'title' => $postUpdateForm->title,
            'body' => $postUpdateForm->body,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => true,
            'statusMessage' => 'Ok',
            'data' => [
                'id' => $postUpdateForm->id,
                'title' => $postUpdateForm->title,
                'body' => $postUpdateForm->body,
            ],
        ]);
    }
}
