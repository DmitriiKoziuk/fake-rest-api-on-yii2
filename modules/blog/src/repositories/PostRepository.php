<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\repositories;

use DmitriiKoziuk\FakeRestApiModules\Blog\entities\Post;
use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostSearchForm;

class PostRepository
{
    public function findPosts(PostSearchForm $postSearchForm): array
    {
        $q = Post::find()
            ->andFilterWhere(['like', 'title', $postSearchForm->title]);
        return [
            'totalItems' => $q->count(),
            'results' => $q->all(),
        ];
    }
}
