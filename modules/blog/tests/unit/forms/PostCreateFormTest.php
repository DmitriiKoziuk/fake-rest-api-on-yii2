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

    /**
     * @param string $title
     * @param string $body
     * @dataProvider notValidDataDataProvider
     */
    public function testWithNotValidData(string $title, string $body)
    {
        $form = new PostCreateForm([
            'title' => $title,
            'body' => $body,
        ]);
        $this->assertFalse($form->validate());
    }

    public function notValidDataDataProvider()
    {
        return [
            'All fields empty' => [
                'title' => '',
                'body' => '',
            ],
            'Too long title' => [
                'title' => str_repeat('t', 256),
                'body' => '',
            ],
        ];
    }
}
