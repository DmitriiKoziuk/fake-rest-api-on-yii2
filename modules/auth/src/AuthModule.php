<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth;

use Yii;
use yii\base\Module;
use yii\base\Application AS BaseApp;
use yii\console\Application AS ConsoleApp;

class AuthModule extends Module
{
    public function init()
    {
        Yii::setAlias('@' . str_replace('\\', '/', __NAMESPACE__), __DIR__);
        /** @var BaseApp $app */
        $app = $this->module;
        $this->registerComponents($app);
    }

    private function registerComponents(BaseApp $app)
    {
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
