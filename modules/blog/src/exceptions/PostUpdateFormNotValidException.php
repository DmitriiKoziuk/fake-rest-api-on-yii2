<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\exceptions;

use Throwable;

class PostUpdateFormNotValidException extends \Exception
{
    protected $message = 'Post update form not valid.';

    protected array $modelErrors = [];

    public function __construct(array $modelErrors)
    {
        parent::__construct($this->message, 0, null);
        $this->modelErrors = $modelErrors;
    }

    public function getModelErrors(): array
    {
        return $this->modelErrors;
    }
}
