<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\forms;

use yii\base\Model;

class UserLoginForm extends Model
{
    public string $username;
    public string $password;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 255],
        ];
    }
}
