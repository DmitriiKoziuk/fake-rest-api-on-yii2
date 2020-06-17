<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\forms;

use yii\base\Model;

class PostSearchForm extends Model
{
    public ?string $title = null;
    public ?string $body = null;
    public int     $page = 1;
    public int     $resultsPerPage = 20;

    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
            [['body'], 'string'],
            [['page', 'resultsPerPage'], 'integer'],
        ];
    }
}