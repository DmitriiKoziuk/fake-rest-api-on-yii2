<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\services;

use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostCreateForm;
use DmitriiKoziuk\FakeRestApiModules\Blog\entities\PostEntity;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostCreateFormNotValidException;

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
}
