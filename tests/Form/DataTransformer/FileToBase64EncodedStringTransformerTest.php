<?php

namespace Hshn\Base64EncodedFile\Form\DataTransformer;

use Hshn\Base64EncodedFile\HttpFoundation\File\UploadedBase64EncodedFile;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class FileToBase64EncodedStringTransformerTest extends TestCase
{
    /**
     * @var DataTransformerInterface
     */
    private $transformer;

    protected function setUp(): void
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
        self::assertEquals($expectedTransformedValue, $this->transformer->transform($value));
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

    public function testTransformInvalidPath()
    {
        $this->expectException(TransformationFailedException::class);
        $this->expectExceptionMessage('invalid path');
        $this->transformer->transform($this->getFile('invalid path'));
    }

    /**
     * @dataProvider provideReverseTransformEmptyTests
     *
     * @param $expectedConstraint
     * @param $value
     */
    public function testReverseTransform(Constraint $expectedConstraint, $value)
    {
        self::assertThat($this->transformer->reverseTransform($value), $expectedConstraint);
    }

    /**
     * @return array
     */
    public function provideReverseTransformEmptyTests()
    {
        return [
            [self::isNull(), null],
            [self::isNull(), ''],
            [self::callback(function ($value) {
                self::assertInstanceOf(UploadedBase64EncodedFile::class, $value);
                /** @var UploadedBase64EncodedFile $value */
                self::assertEquals('foo bar', file_get_contents($value->getPathname()));

                return true;
            }), base64_encode('foo bar')],
        ];
    }

    public function testReverseTransformInvalidChars()
    {
        $this->expectException(TransformationFailedException::class);
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
