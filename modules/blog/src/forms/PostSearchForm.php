<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\forms;

use yii\base\Model;

class PostSearchForm extends Model
{
    public string $title;
    public string $body;

    public function rules()
    {
        return [
            [['title', 'body'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['body'], 'string'],
        ];
    }
}
