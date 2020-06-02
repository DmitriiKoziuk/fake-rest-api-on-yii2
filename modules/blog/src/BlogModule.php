<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog;

use yii\base\Application AS BaseApp;
use yii\web\Application AS WebApp;

class BlogModule extends \yii\base\Module
{
    public function init()
    {
        /** @var BaseApp $app */
        $app = $this->module;
        $this->registerControllers($app);
    }

    private function registerControllers(BaseApp $app)
    {
        if ($app instanceof WebApp && $app->id = 'app-frontend') {
            $this->controllerNamespace = __NAMESPACE__ . '\controllers\frontend';
        }
    }
}
