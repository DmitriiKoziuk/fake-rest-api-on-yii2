<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\controllers\backend;

use yii\rest\Controller;

class SignInController extends Controller
{
    public function actionIndex()
    {
        return 'Sign in get action';
    }
}
