<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\forms;

class UserSignUpFormNotValidException extends \Exception
{
    protected $message = 'User sign up form not valid.';
}
