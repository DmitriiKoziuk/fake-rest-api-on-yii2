<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\controllers\actions;

use Yii;
use yii\base\Action;
use DmitriiKoziuk\FakeRestApiModules\Base\exceptions\InternalApplicationErrorException;
use DmitriiKoziuk\FakeRestApiModules\Blog\repositories\PostRepository;
use DmitriiKoziuk\FakeRestApiModules\Blog\exceptions\PostNotFoundException;

class PostViewAction extends Action
{
    private PostRepository $postRepository;

    public function __construct(
        $id,
        $controller,
        PostRepository $postRepository,
        $config = []
    ) {
        parent::__construct($id, $controller, $config);
        $this->postRepository = $postRepository;
    }

    public function run($id): array
    {
        $return = [
            'success' => false,
            'statusMessage' => '',
            'data' => [],
        ];
        try {
            $post  = $this->postRepository->findPostById((int) $id);
            if (empty($post)) {
                throw new PostNotFoundException();
            }
            $return['data'] = $post;
            $return['success'] = true;
            $return['statusMessage'] = 'Ok';
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
