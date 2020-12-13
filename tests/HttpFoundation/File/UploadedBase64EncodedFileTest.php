<?php


namespace Hshn\Base64EncodedFile\HttpFoundation\File;

use PHPUnit\Framework\TestCase;


/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class UploadedBase64EncodedFileTest extends TestCase
{
    /**
     * @test
     */
    public function testContent()
    {
        $file = $this->getFile('symfony');

        $this->assertEquals('symfony', file_get_contents($file->getPathname()));
    }

    /**
     * @test
     */
    public function testFileName()
    {
        $file = $this->getFile('symfony');
        $this->assertEquals($file->getFilename(), $file->getClientOriginalName());

        $file = $this->getFile('symfony', 'symfony.txt');
        $this->assertEquals('symfony.txt', $file->getClientOriginalName());
    }

    /**
     * @test
     */
    public function testMove()
    {
        $file = $this->getFile('symfony');

        $dir = sys_get_temp_dir();

        $moved = $file->move($dir);

        $this->assertEquals($dir, $moved->getPath());
    }

    /**
     * @test
     */
    public function testIsValid()
    {
        $file = $this->getFile('symfony');

        $this->assertTrue($file->isValid());
    }

    /**
     * @test
     */
    public function testGetError()
    {
        $file = $this->getFile('symfony');

        $this->assertEquals(UPLOAD_ERR_OK, $file->getError());
    }

    /**
     * @param string $content
     * @param string $filename
     *
     * @return UploadedBase64EncodedFile
     */
    private function getFile(string $content, string $filename = ''): UploadedBase64EncodedFile
    {
        return new UploadedBase64EncodedFile(new Base64EncodedFile(base64_encode($content)), $filename);
    }
}
