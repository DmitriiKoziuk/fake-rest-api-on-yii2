<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use DmitriiKoziuk\FakeRestApiModules\Base\exceptions\InternalApplicationErrorException;
use DmitriiKoziuk\FakeRestApiModules\Blog\controllers\actions\PostIndexAction;
use DmitriiKoziuk\FakeRestApiModules\Blog\entities\PostEntity;
use DmitriiKoziuk\FakeRestApiModules\Blog\controllers\actions\PostViewAction;
use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostUpdateForm;
use DmitriiKoziuk\FakeRestApiModules\Blog\services\PostService;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostNotFoundException;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostUpdateFormNotValidException;

class PostController extends ActiveController
{
    public $modelClass = PostEntity::class;

    private PostService $postService;

    public function __construct(
        $id,
        $module,
        PostService $postService,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->postService = $postService;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class'  => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['index'] = [
            'class' => PostIndexAction::class,
        ];
        $actions['view'] = [
            'class' => PostViewAction::class,
        ];
        unset($actions['update']);
        return $actions;
    }

    public function actionUpdate(int $id)
    {
        $return = [
            'success' => false,
            'statusMessage' => '',
            'data' => [],
        ];
        try {
            $postUpdateForm = new PostUpdateForm([
                'id' => $id,
            ]);
            $postUpdateForm->load(Yii::$app->request->getBodyParams(), '');
            if (! $postUpdateForm->validate()) {
                throw new PostUpdateFormNotValidException($postUpdateForm->getErrors());
            }
            $return['data'] = $this->postService->updatePost($postUpdateForm);
            $return['success'] = true;
            $return['statusMessage'] = 'Ok';
        } catch (PostUpdateFormNotValidException $e) {
            $return['statusMessage'] = $e->getMessage();
            $return['data'] = $e->getModelErrors();
        } catch (PostNotFoundException $e) {
            $return['statusMessage'] = $e->getMessage();
        } catch (\Throwable $e) {
            $ex = new InternalApplicationErrorException();
            $return['statusMessage'] = $ex->getMessage();
            Yii::$app->response->statusCode = $ex->statusCode;
            Yii::error($e);
        }
        return $return;
    }
}
