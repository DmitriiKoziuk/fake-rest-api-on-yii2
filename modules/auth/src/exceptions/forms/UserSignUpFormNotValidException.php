<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\forms;

class UserSignUpFormNotValidException extends \Exception
{
    protected $message = 'User sign up form not valid.';
    protected array $formValidationErrors;

    public function __construct(array $formValidationErrors)
    {
        parent::__construct($this->message, 0, null);
        $this->formValidationErrors = $formValidationErrors;
    }

    public function getValidationErrors(): array
    {
        return $this->formValidationErrors;
    }
}
