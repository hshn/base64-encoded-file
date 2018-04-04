<?php

namespace Hshn\Base64EncodedFile\HttpFoundation\File;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeExtensionGuesser;

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
    public function __construct($encoded, $strict = true, $checkPath = true)
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
    private function restoreToTemporary($encoded, $strict = true)
    {
        $fileData = \str_replace('data:', 'data://', $encoded);

        $dataStream = \fopen($fileData, 'r');
        if ($dataStream === false) {
            throw new FileException('Unable to decode strings as base64');
        }

        $fileMeta = \stream_get_meta_data($dataStream);

        if ($strict) {
            if (!isset($fileMeta['base64']) || $fileMeta['base64'] !== true) {
                throw new FileException('Unable to decode strings as base64');
            }
        }

        $fileExtension = (new MimeTypeExtensionGuesser())->guess($fileMeta['mediatype']);

        $filePath = \tempnam($tempDir = \sys_get_temp_dir(), 'Base64EncodedFile');
        if ($filePath === false) {
            throw new FileException(sprintf('Unable to create a file into the "%s" directory', $tempDir));
        }

        $filePath .= '.' . $fileExtension;

        $fileStream = \fopen($filePath, 'w+b');

        if (\stream_copy_to_stream($dataStream, $fileStream) === false) {
            throw new FileException(\sprintf('Unable to write the file "%s"', $filePath));
        }

        if (\fclose($fileStream) === false) {
            throw new FileException(\sprintf('Unable to write the file "%s"', $filePath));
        }

        return $filePath;
    }
}
