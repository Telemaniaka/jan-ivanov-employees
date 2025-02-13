<?php

declare(strict_types=1);

namespace JanIvanov\Employees\Tests\Services;

use JanIvanov\Employees\Services\Solver;
use PHPUnit\Framework\TestCase;
use JanIvanov\Employees\Services\FileCSVReader;

class SolverTest extends TestCase
{
    public function testSolverFindsNoSolutions()
    {
        $fileReader = new FileCSVReader(dirname(__FILE__) . '/../fixtures/fileWithHeaders.csv');
        $solver = new Solver($fileReader);
        $answer = $solver->solve();
        $this->assertEquals([], $answer);
    }

    public function testSolverFindsSolution()
    {
        $fileReader = new FileCSVReader(dirname(__FILE__) . '/../fixtures/fileTest1.csv');
        $solver = new Solver($fileReader);
        $answer = $solver->solve();

        $expected = [[
            'employee1Id' => 143,
            'employee2Id' => 218,
            'days' => 3045,
            'projects' => [
                12 => 7,
                10 => 3038,
            ],
        ]];
        $this->assertEquals($expected, $answer);
    }

}
