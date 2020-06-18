<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\api\frontend;

use yii\helpers\Url;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\ApiTester;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\_fixtures\PostFixture;
use DmitriiKoziuk\FakeRestApiModules\Blog\entities\Post;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostNotFoundException;

class PostViewCest
{
    public function _fixtures()
    {
        return [
            'posts' => PostFixture::class,
        ];
    }

    public function tryToCheckResponseStructureForExistPost(ApiTester $I)
    {
        /** @var Post $postEntity */
        $postEntity = $I->grabFixture('posts', 0);
        $url = '/blog/posts/' . $postEntity->id;

        $I->seeRecord(Post::class, ['id' => $postEntity->id]);
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
        /** @var Post $postEntity */
        $postEntity = $I->grabFixture('posts', 0);
        $url = '/blog/posts/' . $postEntity->id;

        $I->seeRecord(Post::class, ['id' => $postEntity->id]);
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
        /** @var Post $postEntity */
        $postEntity = $I->grabFixture('posts', 0);
        $url = '/blog/posts/' . $postEntity->id;

        $I->seeRecord(Post::class, ['id' => $postEntity->id]);
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

        $I->dontSeeRecord(Post::class, ['id' => $notExistPostId]);
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
