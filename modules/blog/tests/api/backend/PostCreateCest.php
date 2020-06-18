<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\api\backend;

use yii\helpers\Url;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\ApiTester;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserApiKeyEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\User;
use DmitriiKoziuk\FakeRestApiModules\Auth\entities\UserApiKeyEntity;

class PostCreateCest
{
    public function _fixtures()
    {
        return [
            'users' => UserEntityFixture::class,
            'apiKeys' => UserApiKeyEntityFixture::class,
        ];
    }

    public function tryToGetAccessWithoutApiToken(ApiTester $I)
    {
        $I->sendPOST(Url::to(['/blog/posts']));
        $I->seeResponseCodeIs(401);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'name' => 'Unauthorized',
            'message' => 'Your request was made with invalid credentials.',
        ]);
    }

    public function tryToCreateNewPost(ApiTester $I)
    {
        /** @var User $userEntity */
        $userEntity = $I->grabFixture('users', 0);
        /** @var UserApiKeyEntity $userApiKeyEntity */
        $userApiKeyEntity = $I->grabRecord(UserApiKeyEntity::class, ['user_id' => $userEntity->id]);
        $postTitle = 'New post title.';
        $postBody = 'New post body.';

        $I->amBearerAuthenticated($userApiKeyEntity->api_key);
        $I->sendPOST(Url::to(['/blog/posts']), [
            'title' => $postTitle,
            'body' => $postBody,
        ]);

        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'title' => $postTitle,
            'body' => $postBody,
        ]);
    }

    public function tryToCreatePostWithoutAnyData(ApiTester $I)
    {
        /** @var User $userEntity */
        $userEntity = $I->grabFixture('users', 0);
        /** @var UserApiKeyEntity $userApiKeyEntity */
        $userApiKeyEntity = $I->grabRecord(UserApiKeyEntity::class, ['user_id' => $userEntity->id]);

        $I->amBearerAuthenticated($userApiKeyEntity->api_key);
        $I->sendPOST(Url::to(['/blog/posts']));

        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            [
                'field' => 'title',
                'message' => 'Title cannot be blank.',
            ],
            [
                'field' => 'body',
                'message' => 'Body cannot be blank.',
            ],
        ]);
    }
}
