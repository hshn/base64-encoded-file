<?php

namespace Hshn\HttpFoundation\File;

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
        if (false === $decoded = base64_decode($encoded, $strict)) {
            throw new FileException('Could not decode strings as base64');
        }

        if (false === $path = tempnam($directory = sys_get_temp_dir(), 'Base64EncodedFile')) {
            throw new FileException(sprintf('Could not create a file into the "%s" directory', $directory));
        }

        if (false === file_put_contents($path, $decoded, FILE_BINARY)) {
            throw new FileException(sprintf('Could not write the file "%s"', $path));
        }

        return $path;
    }
}
