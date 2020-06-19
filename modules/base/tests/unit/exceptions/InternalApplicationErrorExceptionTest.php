<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Base\tests\unit\exceptions;

use DmitriiKoziuk\FakeRestApiModules\Base\tests\UnitTester;
use DmitriiKoziuk\FakeRestApiModules\Base\exceptions\InternalApplicationErrorException;

class InternalApplicationErrorExceptionTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    // tests
    public function testStatusCode()
    {
        $this->assertEquals(500, (new InternalApplicationErrorException())->statusCode);
    }

    public function testMessage()
    {
        $this->assertEquals(
            'Internal Application Error',
            (new InternalApplicationErrorException())->getMessage()
        );
    }
}
