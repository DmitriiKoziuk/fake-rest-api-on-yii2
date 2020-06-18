<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\controllers\frontend;

use yii\rest\Controller;
use DmitriiKoziuk\FakeRestApiModules\Blog\controllers\actions\PostIndexAction;

class PostController extends Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class' => PostIndexAction::class,
            ],
        ];
    }
}
