<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\controllers\frontend;

use yii\rest\Controller;

class BlogController extends Controller
{
    public function actionIndex()
    {
        return [1, 2];
    }
}
