<?php

namespace Helga;

use finfo;

const PDF_MAGIC = "\x25\x50\x44\x46\x2D";
const OFFICE_MAGIC = "\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1";


function hasCode(string $string)
{
    return (
        (strpos($string, "<?php") !== false) ||
        (strpos($string, " echo ") !== false) ||
        (strpos($string, "__halt_compiler") !== false) ||
        preg_match("/\b\$_GET.+/", $string) ||
        preg_match("/\b\$_POST.+/", $string) ||
        preg_match("/\b\$_REQUEST.+/", $string) ||
        preg_match("/\b\$_COOKIE.+/", $string) ||
        preg_match("/\b\$_SESSION.+/", $string)
    );
}

function containsCode(array $headers)
{
    foreach ($headers as $title => $contents) {
        if (is_array($contents)) {
            containsCode($contents);
        } else if (is_string($contents)) {
            if (hasCode($contents)) {
                return true;
            }
        }
    }
}

function rawContainsCode(string $path)
{
    $contents = file_get_contents($path);
    if (hasCode($contents)) {
        return true;
    }
}


/**
 * Checks if a file is one of several mimes.
 * 
 * @param string $path Path to the file.
 * @param array $mimes An array of allowed mimes.
 * @return bool Returns true on success and false on failure.
 */
function hasMime(string $path, array $mimes)
{
    if (rawContainsCode($path)) {
        return false;
    }

    $fn = new finfo(FILEINFO_MIME);
    $mime = $fn->file($path);
    preg_match("/^[^;]+/", $mime, $part);
    $mime = $part[0] ?? null;
    return in_array($mime, $mimes);
}


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

    $supported = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP, IMAGETYPE_BMP, IMAGETYPE_XBM, IMAGETYPE_WBMP];

    $type = @exif_imagetype($path);
    if (!in_array($type, $supported)) {
        return false;
    }

    if (!hasMime($path, [
        'image/jpeg',
        'image/jpg',
        'image/pjpeg',

        'image/png',
        'image/x-png',

        'image/gif',

        'image/webp',
        'image/x-webp',


        'image/bmp',
        'image/ms-bmp',
        'image/x-bitmap',
        'image/x-bmp',
        'image/x-ms-bmp',
        'image/x-win-bitmap',
        'image/x-windows-bmp',
        'image/x-bitmap',

    ])) {
        return false;
    }

    $data = @exif_read_data($path);
    if (is_array($data)) {
        if (containsCode($data)) {
            return false;
        }
    }

    if (rawContainsCode($path)) {
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
        case IMAGETYPE_BMP:
        case IMAGETYPE_XBM:
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
    if (!is_readable($path) || !hasMime($path, ['application/pdf', 'application/x-pdf'])) {
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
    if (!is_readable($path) || !hasMime($path, ['application/msword', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint'])) {
        return false;
    }
    return (file_get_contents($path, false, null, 0, strlen(OFFICE_MAGIC)) === OFFICE_MAGIC) ? true : false;
}
