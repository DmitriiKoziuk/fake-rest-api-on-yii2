<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\controllers\backend;

use yii\rest\Controller;

class SignUpController extends Controller
{
    public function actionIndex()
    {
        return 'Hello, SignUp';
    }
}
