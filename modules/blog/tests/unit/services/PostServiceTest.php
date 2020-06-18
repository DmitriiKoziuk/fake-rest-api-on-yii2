<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\unit\services;

use Yii;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\UnitTester;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserApiKeyEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\_fixtures\UserEntityFixture;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\_fixtures\PostFixture;
use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostCreateForm;
use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostUpdateForm;
use DmitriiKoziuk\FakeRestApiModules\Blog\entities\PostEntity;
use DmitriiKoziuk\FakeRestApiModules\Blog\services\PostService;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostCreateFormNotValidException;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostUpdateFormNotValidException;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostNotFoundException;

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

    public function testMethodUpdatePostThrowExceptionIfPostUpdateFormNotValid()
    {
        /** @var PostService $postService */
        $postService = Yii::$container->get(PostService::class);
        $postUpdateForm = new PostUpdateForm([
            'title' => '',
            'body' => '',
        ]);

        $this->expectException(PostUpdateFormNotValidException::class);
        $postService->updatePost($postUpdateForm);
    }

    public function testMethodUpdatePostThrowExceptionIfTryUpdateNotExistPost()
    {
        $postId = 9999;
        /** @var PostService $postService */
        $postService = Yii::$container->get(PostService::class);
        $newTitle = 'Updated title';
        $newBoyd  = 'Updated body';
        $postUpdateForm = new PostUpdateForm([
            'id' => $postId,
            'title' => $newTitle,
            'body' => $newBoyd,
        ]);

        $this->tester->dontSeeRecord(PostEntity::class, ['id' => $postId]);
        $this->expectException(PostNotFoundException::class);
        $postService->updatePost($postUpdateForm);
    }

    public function testMethodUpdatePostReturnUpdatedPostData()
    {
        /** @var PostService $postService */
        $postService = Yii::$container->get(PostService::class);
        /** @var PostEntity $postEntity */
        $postEntity = $this->tester->grabFixture('posts', 0);
        $newTitle = 'Updated title';
        $newBoyd  = 'Updated body';
        $postUpdateForm = new PostUpdateForm([
            'id' => $postEntity->id,
            'title' => $newTitle,
            'body' => $newBoyd,
        ]);

        $updatedPostData = $postService->updatePost($postUpdateForm);
        $this->assertIsArray($updatedPostData);
        $this->assertArrayHasKey('id', $updatedPostData);
        $this->assertArrayHasKey('title', $updatedPostData);
        $this->assertArrayHasKey('body', $updatedPostData);
        $this->assertEquals($updatedPostData['id'], $postEntity->id);
        $this->assertEquals($updatedPostData['title'], $newTitle);
        $this->assertEquals($updatedPostData['body'], $newBoyd);
    }
}
