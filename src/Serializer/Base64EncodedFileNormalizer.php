<?php

namespace Hshn\Base64EncodedFile\Serializer;

use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class Base64EncodedFileNormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        return new Base64EncodedFile($data);
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return Base64EncodedFile::class === $type && \is_string($data);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            '*' => true,
        ];
    }
}
