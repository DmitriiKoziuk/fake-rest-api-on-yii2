<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\services;

use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostCreateForm;
use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostUpdateForm;
use DmitriiKoziuk\FakeRestApiModules\Blog\entities\PostEntity;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostCreateFormNotValidException;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostEntityNotValidException;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostNotFoundException;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostUpdateFormNotValidException;

class PostService
{
    public function createPost(PostCreateForm $postCreateForm): array
    {
        if (! $postCreateForm->validate()) {
            throw new PostCreateFormNotValidException();
        }
        $newPostEntity = new PostEntity([
            'title' => $postCreateForm->title,
            'body' => $postCreateForm->body,
        ]);
        $newPostEntity->save();
        return $newPostEntity->getAttributes();
    }

    /**
     * @param PostUpdateForm $postUpdateForm
     * @return array
     * @throws PostNotFoundException
     * @throws PostUpdateFormNotValidException
     * @throws PostEntityNotValidException
     */
    public function updatePost(PostUpdateForm $postUpdateForm): array
    {
        if (! $postUpdateForm->validate()) {
            throw new PostUpdateFormNotValidException($postUpdateForm->getErrors());
        }
        $postEntity = PostEntity::find()
            ->where(['id' => $postUpdateForm->id])
            ->one();
        if (empty($postEntity)) {
            throw new PostNotFoundException();
        }
        $postEntity->setAttributes($postUpdateForm->getAttributes(null, ['id']));
        if (! $postEntity->validate()) {
            throw new PostEntityNotValidException();
        }
        $postEntity->save();
        return $postEntity->getAttributes();
    }
}
