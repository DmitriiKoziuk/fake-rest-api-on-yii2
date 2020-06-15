<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\unit\entities;

use DmitriiKoziuk\FakeRestApiModules\Blog\tests\UnitTester;
use DmitriiKoziuk\FakeRestApiModules\Blog\entities\Post;

class PostEntityTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function testWithValidData()
    {
        $entity = new Post([
            'title' => 't',
            'body' => 'b',
        ]);
        $this->assertTrue($entity->validate());
    }
}
