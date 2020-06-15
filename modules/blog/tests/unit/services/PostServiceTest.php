<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\unit\services;

use Yii;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\UnitTester;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserApiKeyEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\_fixtures\PostFixture;
use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostCreateForm;
use DmitriiKoziuk\FakeRestApiModules\Blog\services\PostService;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostCreateFormNotValidException;

class PostServiceTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function _fixtures()
    {
        return [
            'users' => UserEntityFixture::class,
            'apiKeys' => UserApiKeyEntityFixture::class,
            'posts' => PostFixture::class,
        ];
    }

    public function testMethodCreatePostThrowExceptionIfCreatePostFormNotValid()
    {
        /** @var PostService $postService */
        $postService = Yii::$container->get(PostService::class);
        $postCreateForm = new PostCreateForm([
            'title' => '',
            'body' => '',
        ]);
        $this->expectException(PostCreateFormNotValidException::class);
        $postService->createPost($postCreateForm);
    }

    public function testMethodCreatePostReturnCreatedPostData()
    {
        /** @var PostService $postService */
        $postService = Yii::$container->get(PostService::class);
        $postCreateForm = new PostCreateForm([
            'title' => 'New post title',
            'body' => 'New post body',
        ]);
        $postData = $postService->createPost($postCreateForm);
        $this->assertIsArray($postData);
        $this->assertArrayHasKey('id', $postData);
        $this->assertArrayHasKey('title', $postData);
        $this->assertArrayHasKey('body', $postData);
        $this->assertEquals($postCreateForm->title, $postData['title']);
        $this->assertEquals($postCreateForm->body, $postData['body']);
    }
}
