<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\controllers\backend;

use Yii;
use yii\rest\Controller;
use DmitriiKoziuk\FakeRestApiModules\Auth\forms\UserSignUpForm;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\forms\UserSignUpFormNotValidException;

class SignUpController extends Controller
{
    public function actionIndex()
    {
        return 'Hello, SignUp';
    }

    public function actionCreate(): array
    {
        try {
            $return = [
                'success' => false,
                'statusMessage' => '',
                'data' => [],
            ];
            $userSignUpForm = new UserSignUpForm();
            if (!
                $userSignUpForm->load(Yii::$app->request->post()) &&
                ! $userSignUpForm->validate()
            ) {
                throw new UserSignUpFormNotValidException();
            }
        } catch (UserSignUpFormNotValidException $e) {
            $return['statusMessage'] = $e->getMessage();
        } catch (\Throwable $e) {
            $return['statusMessage'] = 'Internal application error.';
        }
        return $return;
    }
}
