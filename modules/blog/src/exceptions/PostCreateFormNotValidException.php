<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\exceptions;

class PostCreateFormNotValidException extends \Exception
{
    public $message = 'Post create form not valid.';
}
