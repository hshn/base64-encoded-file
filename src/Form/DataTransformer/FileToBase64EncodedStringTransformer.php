<?php

namespace Hshn\Base64EncodedFile\Form\DataTransformer;

use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;
use Hshn\Base64EncodedFile\HttpFoundation\File\UploadedBase64EncodedFile;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;


/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class FileToBase64EncodedStringTransformer implements DataTransformerInterface
{
    /**
     * @var bool
     */
    private $strict;

    /**
     * @param bool $strict
     */
    public function __construct($strict = true)
    {
        $this->strict = $strict;
    }

    /**
     * {@inheritdoc}
     */
    public function transform(mixed $value): mixed
    {
        if (! $value instanceof \SplFileInfo) {
            return '';
        }

        if (false === $file = @file_get_contents($value->getPathname())) {
            throw new TransformationFailedException(sprintf('Unable to read the "%s" file', $value->getPathname()));
        }

        return base64_encode($file);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform(mixed $value): mixed
    {
        if (empty($value)) {
            return null;
        }

        try {
            return new UploadedBase64EncodedFile(new Base64EncodedFile($value, $this->strict));
        } catch (\Exception $e) {
            throw new TransformationFailedException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
