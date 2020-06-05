<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\exceptions;

class UserAlreadyExistException extends \Exception
{
    protected $message = 'User already exist.';
}
