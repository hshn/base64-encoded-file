<?php

namespace Hshn\Base64EncodedFile\HttpFoundation\File;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class Base64EncodedFile extends File
{
    /**
     * @param string $encoded
     * @param bool   $strict
     * @param bool   $checkPath
     */
    public function __construct($encoded, $strict = true, $checkPath = true)
    {
        parent::__construct($this->restoreToTemporary($encoded, $strict), $checkPath);
    }

    /**
     * @param string $encoded
     * @param bool   $strict
     *
     * @return string
     * @throws FileException
     */
    private function restoreToTemporary($encoded, $strict = true)
    {
        if ((false === $decoded = @file_get_contents("data://{$encoded}")) && (false === $decoded = @base64_decode($encoded))) {
            throw new FileException('Unable to decode strings as base64');
        }

        if (false === $path = tempnam($directory = sys_get_temp_dir(), 'Base64EncodedFile')) {
            throw new FileException(sprintf('Unable to create a file into the "%s" directory', $directory));
        }

        if (false === file_put_contents($path, $decoded, FILE_BINARY)) {
            throw new FileException(sprintf('Unable to write the file "%s"', $path));
        }

        return $path;
    }
}
