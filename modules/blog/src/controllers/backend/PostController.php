<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\controllers\backend;

use yii\rest\ActiveController;
use DmitriiKoziuk\FakeRestApiModules\Blog\entities\Post;

class PostController extends ActiveController
{
    public $modelClass = Post::class;
}
