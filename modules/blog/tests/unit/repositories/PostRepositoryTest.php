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

    public function testMethodFindPostsReturnCertainAmountOfPosts()
    {
        $postsPerPage = 3;
        /** @var PostRepository $postRepository */
        $postRepository = Yii::$container->get(PostRepository::class);
        $postSearchForm = new PostSearchForm([
            'title' => 'post',
            'resultsPerPage' => $postsPerPage,
        ]);
        $posts = $postRepository->findPosts($postSearchForm);
        $this->assertIsArray($posts);
        $this->assertArrayHasKey('results', $posts);
        $this->assertIsArray($posts['results']);
        $this->assertCount($postsPerPage, $posts['results']);
    }

    /**
     * @param int $page
     * @param int $resultsPerPage
     * @param array $postsIds
     * @param int $pageSize
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     * @dataProvider postsDataProvider
     */
    public function testMethodFindPostsPaginationWork(int $page, int $resultsPerPage, array $postsIds, int $pageSize)
    {
        /** @var PostRepository $postRepository */
        $postRepository = Yii::$container->get(PostRepository::class);
        $postSearchForm = new PostSearchForm([
            'title' => 'post',
            'page' => $page,
            'resultsPerPage' => $resultsPerPage,
        ]);
        $posts = $postRepository->findPosts($postSearchForm);
        $foundPostsIds = array_column($posts['results'], 'id');
        $this->assertIsArray($posts);
        $this->assertArrayHasKey('results', $posts);
        $this->assertIsArray($posts['results']);
        $this->assertCount($pageSize, $posts['results']);
        $this->assertEmpty(array_diff($postsIds, $foundPostsIds));
    }

    public function postsDataProvider()
    {
        $posts = include __DIR__ . '/../../_fixtures/data/blog_posts.php';
        $resultsPerPage = 3;
        return [
            [
                'page' => 1,
                'resultsPerPage' => $resultsPerPage,
                'postsIds' => array_column(array_slice($posts, 0, $resultsPerPage), 'id'),
                'pageSize' => count(array_column(array_slice($posts, 0, $resultsPerPage), 'id')),
            ],
            [
                'page' => 2,
                'resultsPerPage' => $resultsPerPage,
                'postsIds' => array_column(array_slice($posts, 3, $resultsPerPage), 'id'),
                'pageSize' => count(array_column(array_slice($posts, 3, $resultsPerPage), 'id')),
            ],
            [
                'page' => 3,
                'resultsPerPage' => $resultsPerPage,
                'postsIds' => array_column(array_slice($posts, 6, $resultsPerPage), 'id'),
                'pageSize' => count(array_column(array_slice($posts, 6, $resultsPerPage), 'id')),
            ],
            [
                'page' => 4,
                'resultsPerPage' => $resultsPerPage,
                'postsIds' => array_column(array_slice($posts, 9, $resultsPerPage), 'id'),
                'pageSize' => count(array_column(array_slice($posts, 9, $resultsPerPage), 'id')),
            ],
        ];
    }
}
