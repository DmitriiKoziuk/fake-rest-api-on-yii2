<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Base\exceptions;

use yii\web\ServerErrorHttpException;

class InternalApplicationErrorException extends ServerErrorHttpException
{
    public function __construct(
        $message = 'Internal Application Error',
        $code = 0,
        \Exception
        $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
