<?php

declare(strict_types=1);

namespace JanIvanov\Employees\Services;

class FileCSVReader
{
    /**
     * @var resource
     */
    private $file;

    /**
     * @var string[]
     */
    private $columns = [
        'EmpID',
        'ProjectID',
        'DateFrom',
        'DateTo',
    ];

    public function __construct(string $filename)
    {
        // if file !exists throw exception
        if (!file_exists($filename)) {
            throw new \Exception('File Not Found');
        }
        // open file handler
        $file = fopen($filename, 'r');

        if (!$file) {
            throw new \Exception('File could not be opened');
        }

        $this->file = $file;
        $this->parseHeader();
    }

    public function __destruct()
    {
        fclose($this->file);
    }

    private function parseHeader(): void
    {
        $firstLine = $this->getRawLine();
        if (empty($firstLine)) {
            return;
        }

        if (preg_match('/\d/', $firstLine) > 0) {
            // first line is not a header, rewind
            rewind($this->file);
            return;
        }

        $this->columns = explode(',', $firstLine);
    }

    protected function getRawLine(): ?string
    {
        $line = fgets($this->file);
        if (empty($line)) {
            return null;
        }
        return str_replace(["\r", "\n", ', '], ['','',','], $line);
    }

    /**
     * @return array<string, string>|null
     */
    public function getCsvRow(): ?array
    {
        $line = $this->getRawLine();
        if (empty($line)) {
            return null;
        }
        $data = explode(',', $line);
        return array_combine($this->columns, $data);
    }
}
