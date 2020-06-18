<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\repositories;

use yii\data\ActiveDataProvider;
use DmitriiKoziuk\FakeRestApiModules\Blog\entities\PostEntity;
use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostSearchForm;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostSearchFormNotValidException;

class PostRepository
{
    /**
     * @param PostSearchForm $postSearchForm
     * @return array
     * @throws PostSearchFormNotValidException
     */
    public function findPosts(PostSearchForm $postSearchForm): array
    {
        if (! $postSearchForm->validate()) {
            throw new PostSearchFormNotValidException();
        }
        $q = PostEntity::find()
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

    public function findPostById(int $id): ?PostEntity
    {
        /** @var PostEntity|null $postEntity */
        $postEntity = PostEntity::find()->where(['id' => $id])->one();
        return $postEntity;
    }
}
