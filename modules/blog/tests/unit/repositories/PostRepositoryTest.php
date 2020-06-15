<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\unit\repositories;

use Yii;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\UnitTester;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\_fixtures\PostFixture;
use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostSearchForm;
use DmitriiKoziuk\FakeRestApiModules\Blog\repositories\PostRepository;

class PostRepositoryTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function _fixtures()
    {
        return [
            'posts' => PostFixture::class,
        ];
    }

    public function testMethodFindPosts()
    {
        /** @var PostRepository $postRepository */
        $postRepository = Yii::$container->get(PostRepository::class);
        $postSearchForm = new PostSearchForm([
            'title' => 'post',
        ]);
        $posts = $postRepository->findPosts($postSearchForm);
        $this->assertIsArray($posts);
        $this->assertArrayHasKey('totalItems', $posts);
        $this->assertArrayHasKey('results', $posts);
    }
}
