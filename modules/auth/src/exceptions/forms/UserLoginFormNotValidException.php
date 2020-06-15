<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\exceptions\forms;

class UserLoginFormNotValidException extends \Exception
{
    private array $attributeErrors;

    public function __construct(
        array $attributeErrors
    ) {
        parent::__construct("Invalid user login  data", 0, null);
        $this->attributeErrors = $attributeErrors;
    }

    public function getAttributeErrors()
    {
        return $this->attributeErrors;
    }
}
