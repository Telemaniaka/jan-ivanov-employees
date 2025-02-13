<?php

declare(strict_types=1);

namespace JanIvanov\Employees\Tests\Models;

use DateTime;
use PHPUnit\Framework\TestCase;
use JanIvanov\Employees\Models\ProjectsStorage;

class ProjectsStorageTest extends TestCase
{
    public function testEmployeesThatDontOverlap()
    {
        $projects = new ProjectsStorage();

        $projects->addToProject(
            projectId: 1,
            employeeId: 100,
            startDate: new DateTime('2020-01-01'),
            endDate: new DateTime('2020-01-02')
        );
        $projects->addToProject(
            projectId: 1,
            employeeId: 101,
            startDate: new DateTime('2020-01-02'),
            endDate: new DateTime('2020-01-04')
        );
        $projects->addToProject(
            projectId: 2,
            employeeId: 100,
            startDate: new DateTime('2020-01-03'),
            endDate: new DateTime('2020-01-04')
        );

        $this->assertEquals([], $projects->getEmployeesThatWorkedTogether());
    }

    public function testEmployeesThatOverlap()
    {
        $projects = new ProjectsStorage();

        $projects->addToProject(
            projectId: 1,
            employeeId: 100,
            startDate: new DateTime('2020-01-01'),
            endDate: new DateTime('2020-01-03')
        );
        $projects->addToProject(
            projectId: 1,
            employeeId: 101,
            startDate: new DateTime('2020-01-02'),
            endDate: new DateTime('2020-01-04')
        );
        $projects->addToProject(
            projectId: 2,
            employeeId: 100,
            startDate: new DateTime('2020-01-03'),
            endDate: new DateTime('2020-01-04')
        );

        $expected = [
            '100-101' => [
                'total' => 1,
                'projects' => [
                    1 => 1
                ]
            ]
        ];
        $this->assertEquals($expected, $projects->getEmployeesThatWorkedTogether());
    }
}
