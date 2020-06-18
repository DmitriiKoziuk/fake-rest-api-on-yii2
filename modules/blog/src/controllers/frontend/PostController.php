<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\controllers\frontend;

use Yii;
use yii\rest\Controller;
use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostSearchForm;
use DmitriiKoziuk\FakeRestApiModules\Blog\repositories\PostRepository;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostSearchFormNotValidException;

class PostController extends Controller
{
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

    public function actionIndex()
    {
        try {
            $return = [
                'success' => false,
                'statusMessage' => '',
                'data' => [],
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
