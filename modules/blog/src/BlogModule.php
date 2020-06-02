<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog;

use Yii;
use yii\base\Application AS BaseApp;
use yii\web\Application AS WebApp;
use yii\console\Application AS ConsoleApp;

class BlogModule extends \yii\base\Module
{
    public function init()
    {
        Yii::setAlias('@' . str_replace('\\', '/', __NAMESPACE__), __DIR__);
        /** @var BaseApp $app */
        $app = $this->module;
        $this->registerControllers($app);
    }

    private function registerControllers(BaseApp $app)
    {
        if ($app instanceof WebApp && $app->id = 'app-frontend') {
            $this->controllerNamespace = __NAMESPACE__ . '\controllers\frontend';
        }
        if ($app instanceof ConsoleApp) {
            if (!array_key_exists('migrate', $app->controllerMap)) {
                $app->controllerMap['migrate'] = [
                    'class' => 'yii\console\controllers\MigrateController',
                    'migrationNamespaces' => [],
                ];
            }
            $app->controllerMap['migrate']['migrationNamespaces'][] = __NAMESPACE__ . '\migrations';
        }
    }
}
