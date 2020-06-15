<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\exceptions;

class UserInactiveException extends \Exception
{
    protected $message = 'User inactive.';
}
