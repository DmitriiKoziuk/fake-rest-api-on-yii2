<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\controllers\frontend;

class BlogController extends \yii\web\Controller
{
    public function actionHello()
    {
        return $this->renderContent('Hello, Frontend');
    }
}
