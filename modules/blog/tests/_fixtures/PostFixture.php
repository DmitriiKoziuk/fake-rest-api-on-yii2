<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\_fixtures;

use yii\test\ActiveFixture;
use DmitriiKoziuk\FakeRestApiModules\Blog\entities\Post;

class PostFixture extends ActiveFixture
{
    public $modelClass = Post::class;
}
