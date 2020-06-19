<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\controllers\backend;

use Yii;
use yii\rest\Controller;
use DmitriiKoziuk\FakeRestApiModules\Base\exceptions\InternalApplicationErrorException;
use DmitriiKoziuk\FakeRestApiModules\Auth\forms\UserSignUpForm;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\forms\UserSignUpFormNotValidException;
use DmitriiKoziuk\FakeRestApiModules\Auth\services\UserAuthService;
use DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\UserAlreadyExistException;

class SignUpController extends Controller
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
        return 'Hello, SignUp';
    }

    public function actionCreate(): array
    {
        try {
            $response = [
                'success' => false,
                'statusMessage' => '',
                'data' => [],
            ];
            $response['data'] = $this->userAuthService->signUpUser($this->loadDataToUserSignUpForm());
            $response['success'] = true;
            $response['statusMessage'] = 'User created.';
        } catch (UserSignUpFormNotValidException $e) {
            $response['statusMessage'] = $e->getMessage();
            $response['data'] = $e->getValidationErrors();
        } catch (UserAlreadyExistException $e) {
            $response['statusMessage'] = $e->getMessage();
        } catch (\Throwable $e) {
            $ex = new InternalApplicationErrorException();
            $return['statusMessage'] = $ex->getMessage();
            Yii::$app->response->statusCode = $ex->statusCode;
            Yii::error($e);
        }
        return $response;
    }

    private function loadDataToUserSignUpForm(): UserSignUpForm
    {
        $userSignUpForm = new UserSignUpForm();
        if (!
            $userSignUpForm->load(Yii::$app->request->post(), '') &&
            ! $userSignUpForm->validate()
        ) {
            throw new UserSignUpFormNotValidException($userSignUpForm->getErrors());
        }
        return $userSignUpForm;
    }
}
