<?php


namespace Hshn\Base64EncodedFile\HttpFoundation\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class UploadedBase64EncodedFile extends UploadedFile
{
    /**
     * @param Base64EncodedFile $file
     * @param string            $originalName
     * @param null              $mimeType
     * @param null              $size
     */
    public function __construct(Base64EncodedFile $file, $originalName = '', $mimeType = null, $size = null)
    {
        parent::__construct($file->getPathname(), $originalName ?: $file->getFilename(), $mimeType, $size, null, true);
    }
}
