<?php

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\api\frontend;

use yii\helpers\Url;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\ApiTester;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\_fixtures\PostFixture;

class BlogPostGetCest
{
    public function _fixtures()
    {
        return [
            'postFixture' => PostFixture::class,
        ];
    }

    public function tryGetPostsResource(ApiTester $I)
    {
        $I->sendGET(Url::to(['/blog/posts']));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
