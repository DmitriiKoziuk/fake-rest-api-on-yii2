<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\services;

use DmitriiKoziuk\FakeRestApiModules\Blog\entities\Post;
use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostCreateForm;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostCreateFormNotValidException;

class PostService
{
    public function createPost(PostCreateForm $postCreateForm): array
    {
        if (! $postCreateForm->validate()) {
            throw new PostCreateFormNotValidException();
        }
        $newPost = new Post([
            'title' => $postCreateForm->title,
            'body' => $postCreateForm->body,
        ]);
        $newPost->save();
        return $newPost->getAttributes();
    }
}
