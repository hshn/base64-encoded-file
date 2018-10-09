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
    public function transform($value)
    {
        if (! $value instanceof \SplFileInfo) {
            return '';
        }

        if (false === $file = @file_get_contents($value->getPathname(), FILE_BINARY)) {
            throw new TransformationFailedException(sprintf('Unable to read the "%s" file', $value->getPathname()));
        }

        return base64_encode($file);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (empty($value)) {
            return null;
        }

        if (false === is_string($value)) {
            throw new TransformationFailedException('Expected a Base64 encoded string.');
        }

        try {
            return new UploadedBase64EncodedFile(new Base64EncodedFile($value, $this->strict));
        } catch (\Exception $e) {
            throw new TransformationFailedException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
