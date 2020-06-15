<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\exceptions;

class UserDeletedException extends \Exception
{
    protected $message = 'User deleted.';
}
