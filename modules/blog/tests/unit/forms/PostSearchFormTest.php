<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\tests\unit\forms;

use DmitriiKoziuk\FakeRestApiModules\Blog\forms\PostSearchForm;
use DmitriiKoziuk\FakeRestApiModules\Blog\tests\UnitTester;

class PostSearchFormTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    /**
     * @param null|string $title
     * @param null|string $body
     * @dataProvider validDataDataProvider
     */
    public function testWithValidData(?string $title, ?string $body, ?int $page)
    {
        $form = new PostSearchForm([
            'title' => $title,
            'body' => $body,
        ]);
        $this->assertTrue($form->validate());
    }

    /**
     * @param string $title
     * @param string $body
     * @param int    $page
     * @param array  $invalidFields
     * @dataProvider notValidDataDataProvider
     */
    public function testWithNotValidData(string $title, string $body, int $page, array $invalidFields)
    {
        $form = new PostSearchForm([
            'title' => $title,
            'body' => $body,
            'page' => $page,
        ]);
        $this->assertFalse($form->validate());
        $errors = $form->getErrors();
        $this->assertNotEmpty($errors);
        foreach ($invalidFields as $key => $errorMsg) {
            if (! empty($invalidFields[ $key ])) {
                $this->assertArrayHasKey($key, $errors);
            }
            if (! empty($errorMsg)) {
                $this->assertContains($errorMsg, $errors[ $key ]);
            }
        }
    }

    public function validDataDataProvider()
    {
        return [
            'Title set' => [
                'title' => 'post',
                'body' => null,
                'page' => null,
            ],
            'Body set' => [
                'title' => null,
                'body' => 'post',
                'page' => null,
            ],
            'Page set' => [
                'title' => null,
                'body' => null,
                'page' => 2,
            ],
        ];
    }

    public function notValidDataDataProvider()
    {
        return [
            'Too long title' => [
                'title' => str_repeat('t', 256),
                'body' => '',
                'page' => 1,
                'invalidFields' => [
                    'title' => 'Title should contain at most 255 characters.',
                    'body' => '',
                    'page' => '',
                ],
            ],
        ];
    }
}
