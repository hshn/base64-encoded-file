<?php

namespace Hshn\Base64EncodedFile\HttpFoundation\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 * @author Daniel West <daniel@silverback.is>
 */
class UploadedBase64EncodedFile extends UploadedFile
{
    private Base64EncodedFile $underlying;

    /**
     * @param Base64EncodedFile $file
     * @param string            $originalName
     * @param string|null       $mimeType
     * @param int|null          $size
     */
    public function __construct(Base64EncodedFile $file, $originalName = '', $mimeType = null, $size = null)
    {
        parent::__construct($file->getPathname(), $originalName ?: $file->getFilename(), $mimeType, null, true);

        if ($size !== null) {
            trigger_error('The $size argument is removed since Symfony 5.0 and we will removed it in 6.0.', E_USER_DEPRECATED);
        }

        $this->underlying = $file;
    }
}
