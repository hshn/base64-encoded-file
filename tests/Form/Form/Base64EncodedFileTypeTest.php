<?php

namespace Hshn\Base64EncodedFile\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class Base64EncodedFileTypeTest extends TypeTestCase
{
    /**
     * @test
     * @dataProvider provideTests
     *
     * @param $expectedMimeType
     * @param $filename
     */
    public function test($expectedMimeType, $filename)
    {
        $form = $this->createForm();

        $form->submit(base64_encode(file_get_contents(__DIR__.'/Fixtures/'.$filename)));

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());

        /** @var $file File */
        $file = $form->getData();
        $this->assertInstanceOf('Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile', $file);

        $this->assertEquals($expectedMimeType, $file->getMimeType());
    }

    /**
     * @return array
     */
    public function provideTests()
    {
        return [
            ['text/plain', 'file.txt'],
            ['image/png', 'file.png'],
            ['image/gif', 'file.gif'],
            ['image/jpeg', 'file.jpg'],
        ];
    }

    /**
     * @test
     */
    public function testEmpty()
    {
        $form = $this->createForm();

        $form->submit('');

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());

        /** @var $file File */
        $file = $form->getData();
        $this->assertNull($file);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createForm()
    {
        return $this->factory->create(new Base64EncodedFileType());
    }
}
