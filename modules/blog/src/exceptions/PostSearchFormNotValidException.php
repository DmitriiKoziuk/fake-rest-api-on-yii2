<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\exceptions;

class PostSearchFormNotValidException extends \Exception
{
    public $message = 'Post search form not valid.';
}
