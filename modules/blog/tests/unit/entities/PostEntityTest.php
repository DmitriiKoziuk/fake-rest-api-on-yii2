<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\unit\entities;

use DmitriiKoziuk\FakeRestApiModules\Blog\tests\UnitTester;
use DmitriiKoziuk\FakeRestApiModules\Blog\entities\PostEntity;

class PostEntityTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function testWithValidData()
    {
        $entity = new PostEntity([
            'title' => 't',
            'body' => 'b',
        ]);
        $this->assertTrue($entity->validate());
    }

    /**
     * @param string $title
     * @param string $body
     * @param array  $invalidFields
     * @dataProvider notValidDataDataProvider
     */
    public function testWithNotValidData(string $title, string $body, array $invalidFields)
    {
        $entity = new PostEntity([
            'title' => $title,
            'body' => $body,
        ]);
        $this->assertFalse($entity->validate());
        $errors = $entity->getErrors();
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
