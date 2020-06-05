<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\forms;

use yii\base\Model;

class UserSignUpForm extends Model
{
    public ?string $username = null;
    public ?string $email = null;
    public ?string $password = null;

    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            [['username'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 254],
            [['password'], 'string', 'max' => 255],
            ['email', 'email'],
        ];
    }
}
