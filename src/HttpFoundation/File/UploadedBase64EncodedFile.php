<?php

namespace Hshn\Base64EncodedFile\HttpFoundation\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 * @author Daniel West <daniel@silverback.is>
 */
class UploadedBase64EncodedFile extends UploadedFile
{
    /**
     * @param Base64EncodedFile $file
     * @param string            $originalName
     * @param string|null       $mimeType
     * @param int|null          $size
     */
    public function __construct(Base64EncodedFile $file, $originalName = '', $mimeType = null, $size = null)
    {
        $method = new \ReflectionMethod(parent::class, '__construct');
        $num = $method->getNumberOfParameters();
        if (5 === $num) {
            parent::__construct($file->getPathname(), $originalName ?: $file->getFilename(), $mimeType, null, true);
        } else {
            // Symfony 4 compatible
            parent::__construct($file->getPathname(), $originalName ?: $file->getFilename(), $mimeType, $size, null, true);
        }
    }
}
