<?php

namespace Hshn\HttpFoundation\File;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class Base64EncodedFileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function testThrowExceptionUnlessValidChars()
    {
        new Base64EncodedFile('@');
    }

    /**
     * @test
     */
    public function testCreateInstance()
    {
        $rawStrings = 'symfony2';
        $file = new Base64EncodedFile(base64_encode($rawStrings));

        $this->assertFileExists($file->getPathname());
        $this->assertEquals($rawStrings, file_get_contents($file->getPathname()));
    }
}
