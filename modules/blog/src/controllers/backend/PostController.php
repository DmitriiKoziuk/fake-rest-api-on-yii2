<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use DmitriiKoziuk\FakeRestApiModules\Blog\entities\Post;
use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostSearchForm;
use DmitriiKoziuk\FakeRestApiModules\Blog\repositories\PostRepository;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostSearchFormNotValidException;

class PostController extends ActiveController
{
    public $modelClass = Post::class;

    private PostRepository $postRepository;

    public function __construct(
        $id,
        $module,
        PostRepository $postRepository,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->postRepository = $postRepository;
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
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        try {
            $return = [
                'success' => false,
                'statusMessage' => '',
                'data' => '',
            ];
            $postSearchForm = new PostSearchForm();
            if (Yii::$app->request->isGet) {
                $postSearchForm->load(Yii::$app->request->get(), '');
            }
            if (! $postSearchForm->validate()) {
                throw new PostSearchFormNotValidException();
            }
            $return['data'] = $this->postRepository->findPosts($postSearchForm);
            $return['data']['page'] = $postSearchForm->page;
            $return['data']['resultsPerPage'] = $postSearchForm->resultsPerPage;
            $return['success'] = true;
            $return['statusMessage'] = 'Ok';
        } catch (PostSearchFormNotValidException $e) {
            $return['statusMessage'] = $e->getMessage();
        } catch (\Throwable $e) {
            $return['statusMessage'] = 'Internal application error.';
        }
        return $return;
    }
}
