<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\controllers\actions;

use Yii;
use yii\base\Action;
use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostSearchForm;
use DmitriiKoziuk\FakeRestApiModules\Blog\repositories\PostRepository;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostSearchFormNotValidException;

class PostIndexAction extends Action
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

    public function run(): array
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
