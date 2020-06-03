<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\controllers\backend;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use DmitriiKoziuk\FakeRestApiModules\Blog\entities\Post;

class PostController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class'  => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    public $modelClass = Post::class;
}
