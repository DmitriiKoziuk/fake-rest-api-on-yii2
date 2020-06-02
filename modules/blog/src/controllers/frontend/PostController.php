<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\controllers\frontend;

use yii\rest\Controller;
use DmitriiKoziuk\FakeRestApiModules\Blog\entities\Post;

class PostController extends Controller
{
    public function actionIndex()
    {
        return Post::find()->all();
    }
}
