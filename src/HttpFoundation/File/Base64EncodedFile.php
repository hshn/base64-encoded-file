<?php

namespace Hshn\Base64EncodedFile\HttpFoundation\File;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeExtensionGuesser;
use Symfony\Component\Mime\MimeTypes;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class Base64EncodedFile extends File
{
    /**
     * @param string $encoded
     * @param bool $strict
     * @param bool $checkPath
     */
    public function __construct(string $encoded, bool $strict = true, bool $checkPath = true)
    {
        parent::__construct($this->restoreToTemporary($encoded, $strict), $checkPath);
    }

    /**
     * @param string $encoded
     * @param bool $strict
     *
     * @return string
     * @throws FileException
     */
    private function restoreToTemporary(string $encoded, $strict = true): string
    {
        if (strpos($encoded, 'data:') === 0) {
            if (strpos($encoded, 'data://') !== 0) {
                $encoded = substr_replace($encoded, 'data://', 0, 5);
            }

            $source = @fopen($encoded, 'rb');
            if ($source === false) {
                throw new FileException('Unable to decode strings as base64');
            }

            $meta = stream_get_meta_data($source);

            if ($strict) {
                if (!isset($meta['base64']) || $meta['base64'] !== true) {
                    throw new FileException('Unable to decode strings as base64');
                }
            }

            if (false === $path = tempnam($directory = sys_get_temp_dir(), 'Base64EncodedFile')) {
                throw new FileException(sprintf('Unable to create a file into the "%s" directory', $path));
            }

            if( is_array($meta) && array_key_exists('mediatype', $meta) && $meta['mediatype'] !== null ) {
                if (null !== $extension = (MimeTypes::getDefault()->getExtensions($meta['mediatype'])[0] ?? null)) {
                    $path .= '.' . $extension;
                }
            }

            if (false === $target = @fopen($path, 'w+b')) {
                throw new FileException(sprintf('Unable to write the file "%s"', $path));
            }

            if (false === @stream_copy_to_stream($source, $target)) {
                throw new FileException(sprintf('Unable to write the file "%s"', $path));
            }

            if (false === @fclose($target)) {
                throw new FileException(sprintf('Unable to write the file "%s"', $path));
            }

            if (false === @fclose($source)) {
                throw new FileException(sprintf('Unable to close data stream'));
            }

            return $path;
        }

        if (false === $decoded = base64_decode($encoded, $strict)) {
            throw new FileException('Unable to decode strings as base64');
        }

        if (false === $path = tempnam($directory = sys_get_temp_dir(), 'Base64EncodedFile')) {
            throw new FileException(sprintf('Unable to create a file into the "%s" directory', $directory));
        }

        if (false === file_put_contents($path, $decoded)) {
            throw new FileException(sprintf('Unable to write the file "%s"', $path));
        }

        return $path;
    }

    public function __destruct()
    {
        if (file_exists($this->getPathname())) {
            unlink($this->getPathname());
        }
    }
}
