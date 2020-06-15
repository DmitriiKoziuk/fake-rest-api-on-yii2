<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\controllers\backend;

use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\UserInactiveException;
use Yii;
use yii\rest\Controller;
use DmitriiKoziuk\FakeRestApiModules\Auth\forms\UserLoginForm;
use DmitriiKoziuk\FakeRestApiModules\Auth\services\UserAuthService;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\forms\UserLoginFormNotValidException;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\UserNotFoundException;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\UserPasswordIncorrectException;

class SignInController extends Controller
{
    private UserAuthService $userAuthService;

    public function __construct(
        $id,
        $module,
        UserAuthService $userAuthService,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->userAuthService = $userAuthService;
    }

    public function actionIndex()
    {
        return 'Hello';
    }

    public function actionCreate()
    {
        try {
            $return = [
                'success' => false,
                'statusMessage' => '',
                'data' => '',
            ];
            $userLoginForm = new UserLoginForm();
            if (
                ! $userLoginForm->load(Yii::$app->request->post(), '') ||
                ! $userLoginForm->validate()
            ) {
                throw new UserLoginFormNotValidException($userLoginForm->getErrors());
            }
            $return['data'] = $this->userAuthService->signInUser($userLoginForm);
            $return['success'] = true;
            $return['statusMessage'] = 'Ok';
        } catch (UserLoginFormNotValidException $e) {
            $return['statusMessage'] = $e->getMessage();
            $return['data'] = $e->getAttributeErrors();
        } catch (UserNotFoundException $e) {
            $return['statusMessage'] = $e->getMessage();
        } catch (UserInactiveException $e) {
            $return['statusMessage'] = $e->getMessage();
        } catch (UserPasswordIncorrectException $e) {
            $return['statusMessage'] = $e->getMessage();
        } catch (\Throwable $e) {
            $return['statusMessage'] = 'Internal application error.';
        }
        return $return;
    }
}
