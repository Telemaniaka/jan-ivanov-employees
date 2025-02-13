<?php

namespace JanIvanov\Employees\Services;

class FileUploadHandler
{
    private string $fileName;
    private bool $hasError = false;
    private string $errorMessage = 'Unknown Error';

    public const PHP_FILE_UPLOAD_ERRORS = [
        0 => 'There is no error, the file uploaded with success',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.',
    ];

    public function __construct()
    {
        if (empty($_FILES) || empty($_FILES["csvFile"])) {
            $this->triggerError('No file uploaded');
            return;
        }

        // @phpstan-ignore-next-line
        if ($_FILES["csvFile"]["error"] > 0) {
            $this->triggerError(self::PHP_FILE_UPLOAD_ERRORS[$_FILES["csvFile"]["error"]]);
            return;
        }

        // @phpstan-ignore-next-line
        $this->fileName = (string) $_FILES["csvFile"]["tmp_name"];

        if (!$this->isCSV($this->fileName)) {
        // @phpstan-ignore-next-line
            $this->triggerError('The file "' . $_FILES["csvFile"]["name"] . '" does not appear to be CSV');
        }
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getHasError(): bool
    {
        return $this->hasError;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    private function triggerError(string $errorMessage): void
    {
        $this->hasError = true;
        $this->errorMessage = $errorMessage;
    }

    private function isCSV(string $tmp_name): bool
    {
        // list courtesy of
        // https://stackoverflow.com/questions/50870138/php-check-whether-a-imported-file-is-a-csv-or-not
        $csvMimes = [
            'text/x-comma-separated-values',
            'text/comma-separated-values',
            'application/octet-stream',
            'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            'application/excel',
            'application/vnd.msexcel',
            'text/plain',
        ];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo === false) {
            return false;
        }
        $mime = finfo_file($finfo, $tmp_name);
        finfo_close($finfo);
        return in_array($mime, $csvMimes);
    }

    public function __destruct()
    {
        if (isset($this->fileName)) {
            unlink($this->fileName);
        }
    }
}
