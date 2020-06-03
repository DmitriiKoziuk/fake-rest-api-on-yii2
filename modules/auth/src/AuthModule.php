<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth;

use Yii;
use yii\base\Module;

class AuthModule extends Module
{
    public function init()
    {
        Yii::setAlias('@' . str_replace('\\', '/', __NAMESPACE__), __DIR__);
    }
}
