<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\unit\forms;

use DmitriiKoziuk\FakeRestApiModules\Blog\tests\UnitTester;
use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostCreateForm;

class PostCreateFormTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function testWithValidData()
    {
        $form = new PostCreateForm([
            'title' => 't',
            'body' => 'b',
        ]);
        $this->assertTrue($form->validate());
    }
}
