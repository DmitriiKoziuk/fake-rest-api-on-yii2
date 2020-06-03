<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\tests\unit;

use Yii;
use DmitriiKoziuk\FakeRestApiModules\Auth\tests\UnitTester;

class ModuleTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function testIsModuleLoaded()
    {
        $this->assertTrue(Yii::$app->hasModule('auth'));
    }
}
