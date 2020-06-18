<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\forms;

class PostUpdateForm extends PostCreateForm
{
    public ?int $id = null;
    public string $title = '';
    public string $body = '';

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['id'], 'required'];
        $rules[] = [['id'], 'integer'];
        return $rules;
    }
}
