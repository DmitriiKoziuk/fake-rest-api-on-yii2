<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\api\frontend;

use yii\helpers\Url;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\ApiTester;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\_fixtures\PostFixture;

class PostGetCest
{
    public function _fixtures()
    {
        return [
            'posts' => PostFixture::class,
        ];
    }

    public function tryGetPostsResource(ApiTester $I)
    {
        $I->sendGET(Url::to(['/blog/posts']));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function tryCheckIsResponseDataStructureCorrect(ApiTester $I)
    {
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
