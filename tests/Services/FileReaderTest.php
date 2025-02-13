<?php

declare(strict_types=1);

namespace JanIvanov\Employees\Tests\Services;

use PHPUnit\Framework\TestCase;
use JanIvanov\Employees\Services\FileCSVReader;

class FileReaderTest extends TestCase
{
    public function testThrowExceptionOnNonExistingFile()
    {
        $this->expectExceptionMessage('File Not Found');
        new FileCSVReader('NonExistingFile.csv');
    }

    public function testFileReaderGetsInitialised()
    {
        $fileReader = new FileCSVReader(dirname(__FILE__) . '/../fixtures/fileWithHeaders.csv');

        $this->assertNotNull($fileReader);
    }

    public function testFileReaderSkipsHeaderRow()
    {
        $fileReader = new FileCSVReader(dirname(__FILE__) . '/../fixtures/fileWithHeaders.csv');

        $this->assertEquals([
            'EmpID' => 143,
            'ProjectID' => 12,
            'DateFrom' => '2013-11-01',
            'DateTo' => '2014-01-05'], $fileReader->getCsvRow());
    }

    public function testFileReaderDoesNotSkipIfNoHeader()
    {
        $fileReader = new FileCSVReader(dirname(__FILE__) . '/../fixtures/fileWithoutHeaders.csv');

        $this->assertEquals([
            'EmpID' => 143,
            'ProjectID' => 12,
            'DateFrom' => '2013-11-01',
            'DateTo' => '2014-01-05'], $fileReader->getCsvRow());
    }
}
