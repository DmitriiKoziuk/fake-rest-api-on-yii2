<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\controllers\backend;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use DmitriiKoziuk\FakeRestApiModules\Blog\controllers\actions\PostIndexAction;
use DmitriiKoziuk\FakeRestApiModules\Blog\entities\PostEntity;
use DmitriiKoziuk\FakeRestApiModules\Blog\controllers\actions\PostViewAction;

class PostController extends ActiveController
{
    public $modelClass = PostEntity::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class'  => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['index'] = [
            'class' => PostIndexAction::class,
        ];
        $actions['view'] = [
            'class' => PostViewAction::class,
        ];
        return $actions;
    }
}
