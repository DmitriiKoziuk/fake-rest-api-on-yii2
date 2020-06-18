<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\exceptions;

class PostNotFoundException extends \Exception
{
    protected $message = 'Post not found.';
}
