<?php

namespace Hshn\Base64EncodedFile\Serializer;

use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class Base64EncodedFileNormalizer implements DenormalizerInterface
{
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        return new Base64EncodedFile($data);
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return Base64EncodedFile::class === $type && \is_string($data);
    }
}
