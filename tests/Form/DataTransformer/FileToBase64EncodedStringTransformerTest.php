<?php

namespace Hshn\Base64EncodedFile\Form\DataTransformer;

use Hshn\Base64EncodedFile\HttpFoundation\File\UploadedBase64EncodedFile;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class FileToBase64EncodedStringTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DataTransformerInterface
     */
    private $transformer;

    protected function setUp()
    {
        $this->transformer = new FileToBase64EncodedStringTransformer();
    }

    /**
     * @test
     * @dataProvider provideTransformEmptyTests
     *
     * @param $expectedTransformedValue
     * @param $value
     */
    public function testTransform($expectedTransformedValue, $value)
    {
        $this->assertEquals($expectedTransformedValue, $this->transformer->transform($value));
    }

    /**
     * @return array
     */
    public function provideTransformEmptyTests()
    {
        return [
            ['', null],
            ['', ''],
            ['', new \stdClass()],
            [base64_encode(file_get_contents(__FILE__)), $this->getFile(__FILE__)],
        ];
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     * @expectedExceptionMessage invalid path
     */
    public function testTransformInvalidPath()
    {
        $this->transformer->transform($this->getFile('invalid path'));
    }

    /**
     * @test
     * @dataProvider provideReverseTransformEmptyTests
     *
     * @param $expectedConstraint
     * @param $value
     */
    public function testReverseTransform($expectedConstraint, $value)
    {
        if (! $expectedConstraint instanceof \PHPUnit_Framework_Constraint) {
            $expectedConstraint = $this->equalTo($expectedConstraint);
        }

        $this->assertThat($this->transformer->reverseTransform($value), $expectedConstraint);
    }

    /**
     * @test
     */
    public function provideReverseTransformEmptyTests()
    {
        return [
            [null, null],
            [null, ''],
            [$this->callback(function ($value) {
                /** @var $value UploadedBase64EncodedFile */
                $this->assertInstanceOf('Hshn\Base64EncodedFile\HttpFoundation\File\UploadedBase64EncodedFile', $value);
                $this->assertEquals('foo bar', file_get_contents($value->getPathname()));

                return true;
            }), base64_encode('foo bar')],
        ];
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function testReverseTransformInvalidChars()
    {
        $this->transformer->reverseTransform('@');
    }

    /**
     * @param $path
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getFile($path)
    {
        $file = $this->getMockBuilder('SplFileInfo')->setConstructorArgs(['mocked file'])->getMock();

        $file
            ->expects($this->atLeastOnce())
            ->method('getPathname')
            ->will($this->returnValue($path));

        return $file;
    }
}
