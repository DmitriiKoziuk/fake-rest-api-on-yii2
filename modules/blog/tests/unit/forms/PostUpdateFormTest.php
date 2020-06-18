<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\unit\forms;

use DmitriiKoziuk\FakeRestApiModules\Blog\tests\UnitTester;
use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostUpdateForm;

class PostUpdateFormTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function testWithValidData()
    {
        $form = new PostUpdateForm([
            'id' => 1,
            'title' => 't',
            'body' => 'b',
        ]);
        $this->assertTrue($form->validate());
    }

    /**
     * @param int|null $id
     * @param string   $title
     * @param string   $body
     * @param array    $invalidFields
     * @dataProvider notValidDataDataProvider
     */
    public function testWithNotValidData(?int $id, string $title, string $body, array $invalidFields)
    {
        $form = new PostUpdateForm([
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
                'id' => null,
                'title' => '',
                'body' => '',
                'invalidFields' => [
                    'id' => 'Id cannot be blank.',
                    'title' => 'Title cannot be blank.',
                    'body' => 'Body cannot be blank.',
                ],
            ],
            'Too long title' => [
                'id' => 1,
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
