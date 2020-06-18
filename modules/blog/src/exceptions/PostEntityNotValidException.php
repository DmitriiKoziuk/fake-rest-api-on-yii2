<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\exceptions;

class PostEntityNotValidException extends \Exception
{
    protected $message = 'Post entity not valid.';
}
