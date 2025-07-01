<?php
const ALLOWED_IMAGE_TYPES = [
    'image/png' => 'png',
    'image/jpeg' => 'jpg',
];

const FILE_MESSAGES = [
    UPLOAD_ERR_OK => 'File uploaded successfully!',
    UPLOAD_ERR_INI_SIZE => 'File is too large!',
    UPLOAD_ERR_FORM_SIZE => 'File is too large!',
    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded!',
    UPLOAD_ERR_NO_FILE => 'No file was uploaded!',
    UPLOAD_ERR_NO_TMP_DIR => 'No temporary directory was found!',
    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk!',
    UPLOAD_ERR_EXTENSION => 'File is not allowed to upload to this server!',
];

const MAX_FILE_SIZE = 5 * 1024 * 1024;

function getMimeType(string $filename): string|false  {
    $info = finfo_open(FILEINFO_MIME_TYPE);
    if (!$info) {
        return false;
    }

    $mimetype = finfo_file($info, $filename);
    finfo_close($info);

    return $mimetype;
}

function formatFilesize(int $bytes, int $decimals = 2): string {
    $units = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);

    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $units[(int)$factor];
}