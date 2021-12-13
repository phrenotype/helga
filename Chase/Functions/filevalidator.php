<?php

namespace Chase;

const PDF_MAGIC = "\x25\x50\x44\x46\x2D";
const OFFICE_MAGIC = "\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1";

/**
 * Returns true if the path is a valid Image file based on file signature.
 *
 * @param string $path
 * @return bool
 */
function isImage(string $path)
{

    if (!is_readable($path)) {
        return false;
    }

    $supported = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP];

    $type = exif_imagetype($path);

    if (!in_array($type, $supported)) {
        return false;
    }

    $image = false;
    switch ($type) {
        case IMAGETYPE_GIF:
            $image = imagecreatefromgif($path);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($path);
            break;
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($path);
            break;
        case IMAGETYPE_WEBP:
            $image = imagecreatefromwebp($path);
            break;
    }
    return (!!$image);
}


/**
 * Returns true if the path is a valid pdf file, based on file signature.
 * 
 * @param string $path
 * @return bool
 */
function isPDF($path)
{
    if (!is_readable($path)) {
        return false;
    }
    return (file_get_contents($path, false, null, 0, strlen(PDF_MAGIC)) === PDF_MAGIC) ? true : false;
}

/**
 * Returns true if the path is a valid office (doc, ppt, xls, msi, msg) file.
 * 
 * @param string $path
 * @return bool
 */
function isOffice($path)
{
    if (!is_readable($path)) {
        return false;
    }
    return (file_get_contents($path, false, null, 0, strlen(OFFICE_MAGIC)) === OFFICE_MAGIC) ? true : false;
}
