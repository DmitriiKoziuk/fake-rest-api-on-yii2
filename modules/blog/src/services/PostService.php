<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\services;

use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostCreateForm;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostCreateFormNotValidException;

class PostService
{
    public function createPost(PostCreateForm $postCreateForm)
    {
        if (! $postCreateForm->validate()) {
            throw new PostCreateFormNotValidException();
        }
    }
}
