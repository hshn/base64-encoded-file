<?php

declare(strict_types=1);

namespace Hshn\Base64EncodedFile\Serializer;

use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;
use PHPUnit\Framework\TestCase;

/**
 * @author Vincent Chalamon <vincent@les-tilleuls.coop>
 */
final class Base64EncodedFileNormalizerTest extends TestCase
{
    const BASE64_STRING = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAAAAAA6fptVAAAACklEQVQYV2P4DwABAQEAWk1v8QAAAABJRU5ErkJggg==';

    /**
     * @var Base64EncodedFileNormalizer
     */
    private $normalizer;

    protected function setUp(): void
    {
        $this->normalizer = new Base64EncodedFileNormalizer();
    }

    public function testItDoesNotSupportInvalidFormat()
    {
        $this->assertFalse($this->normalizer->supportsDenormalization(self::BASE64_STRING, 'invalid'));
    }

    public function testItDoesNotSupportInvalidData()
    {
        $this->assertFalse($this->normalizer->supportsDenormalization(3, Base64EncodedFile::class));
    }

    public function testItSupportsValidFormatAndData()
    {
        $this->assertTrue($this->normalizer->supportsDenormalization(self::BASE64_STRING, Base64EncodedFile::class));
    }

    public function testItDenormalizesAStringToABase64EncodedFileObject()
    {
        $this->assertInstanceOf(Base64EncodedFile::class, $this->normalizer->denormalize(self::BASE64_STRING, Base64EncodedFile::class));
    }
}
