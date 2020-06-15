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
     * @param array  $invalidFields
     * @dataProvider notValidDataDataProvider
     */
    public function testWithNotValidData(string $title, string $body, array $invalidFields)
    {
        $form = new PostCreateForm([
            'title' => $title,
            'body' => $body,
        ]);
        $this->assertFalse($form->validate());
        $errors = $form->getErrors();
        $this->assertNotEmpty($errors);
        foreach ($invalidFields as $key => $errorMsg) {
            $this->assertArrayHasKey($key, $errors);
            if (! empty($errorMsg)) {
                $this->assertContains($errorMsg, $errors[ $key ]);
            }
        }
    }

    public function notValidDataDataProvider()
    {
        return [
            'All fields empty' => [
                'title' => '',
                'body' => '',
                'invalidFields' => [
                    'title' => 'Title cannot be blank.',
                    'body' => 'Body cannot be blank.',
                ],
            ],
            'Too long title' => [
                'title' => str_repeat('t', 256),
                'body' => '',
                'invalidFields' => [
                    'title' => 'Title should contain at most 255 characters.',
                    'body' => '',
                ],
            ],
        ];
    }
}
