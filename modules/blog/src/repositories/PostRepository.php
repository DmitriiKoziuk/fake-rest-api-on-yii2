<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\repositories;

use yii\data\ActiveDataProvider;
use DmitriiKoziuk\FakeRestApiModules\Blog\entities\Post;
use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostSearchForm;

class PostRepository
{
    public function findPosts(PostSearchForm $postSearchForm): array
    {
        $q = Post::find()
            ->andFilterWhere(['like', 'title', $postSearchForm->title]);
        $dataProvider = new ActiveDataProvider([
            'query' => $q,
            'pagination' => [
                'pageSize' => $postSearchForm->resultsPerPage,
                'page' => $postSearchForm->page - 1,
            ],
        ]);
        return [
            'totalItems' => $q->count(),
            'results' => $dataProvider->getModels(),
        ];
    }
}
