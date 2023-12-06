<?php

namespace Hshn\Base64EncodedFile\HttpFoundation\File;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class Base64EncodedFileTest extends TestCase
{
    public function testThrowExceptionUnlessValidChars()
    {
        $this->expectException(FileException::class);
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

    /**
     * @test
     */
    public function testCreateInstanceWithData()
    {
        $rawStrings = 'symfony2';
        $file = new Base64EncodedFile('data:text/plain;base64,' . base64_encode($rawStrings));

        $this->assertFileExists($file->getPathname());
        $this->assertEquals($rawStrings, file_get_contents($file->getPathname()));
        $this->assertEquals('txt', pathinfo($file->getPathname(), PATHINFO_EXTENSION));
    }

    /**
     * @test
     */
    public function testCleanupTemporaryFilesOnDestruction()
    {
        $rawStrings = 'symfony2';
        $file = new Base64EncodedFile(base64_encode($rawStrings));

        $pathname = $file->getPathname();

        $this->assertTrue(file_exists($pathname));

        unset($file);

        $this->assertFalse(file_exists($pathname));
    }
}
