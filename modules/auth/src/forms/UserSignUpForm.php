<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\forms;

use yii\base\Model;

class UserSignUpForm extends Model
{
    public string $username;
    public string $email;
    public string $password;

    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            [['username'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 255],
        ];
    }
}
