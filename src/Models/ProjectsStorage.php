<?php

declare(strict_types=1);

namespace JanIvanov\Employees\Models;

use DateTime;

class ProjectsStorage
{
    /**
     * @var array<int, array{'employeeId':int,'startDate':DateTime,'endDate':DateTime}[]>
     */
    private array $projects = [];

    public function addToProject(int $projectId, int $employeeId, DateTime $startDate, DateTime $endDate): void
    {
        if (!array_key_exists($projectId, $this->projects)) {
            $this->projects[$projectId] = [];
        }

        $this->projects[$projectId][] = [
            'employeeId' => $employeeId,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }

    /**
     * @return array<string, array{'total':int, 'projects':array<int, int>}>
     */
    public function getEmployeesThatWorkedTogether(): array
    {
        $output = [];
        foreach (array_keys($this->projects) as $projectId) {
            $projectWorkTimes = $this->calculateEmployeesTimeTogetherOnProject($projectId);
            foreach ($projectWorkTimes as $pair => $workTime) {
                if (!array_key_exists($pair, $output)) {
                    $output[$pair] = [
                        'total' => 0,
                        'projects' => [],
                    ];
                }
                $output[$pair]['total'] += $workTime;
                $output[$pair]['projects'][$projectId] = $workTime;
            }
        }

        return $output;
    }

    /**
     * @return array{}|array<string, int>
     */
    private function calculateEmployeesTimeTogetherOnProject(int $projectId): array
    {
        $output = [];
        $employees = $this->projects[$projectId];
        $employeeCount = count($employees);
        for ($i = 0; $i < $employeeCount; $i++) {
            for ($j = $i + 1; $j < $employeeCount; $j++) {
                if ($employees[$i]['employeeId'] == $employees[$j]['employeeId']) {
                    continue;
                }

                //do they overlap
                if (
                    $employees[$i]['startDate'] >= $employees[$j]['endDate']
                    || $employees[$i]['endDate'] <= $employees[$j]['startDate']
                ) {
                    continue;
                }

                $days = min(
                    $employees[$i]['endDate'],
                    $employees[$j]['endDate']
                )->diff(
                    max(
                        $employees[$i]['startDate'],
                        $employees[$j]['startDate']
                    )
                )->days;
                $leftSide = min($employees[$i]['employeeId'], $employees[$j]['employeeId']);
                $rightSide = max($employees[$i]['employeeId'], $employees[$j]['employeeId']);

                $key = $leftSide . '-' . $rightSide;
                if (!isset($output[$key])) {
                    $output[$key] = 0;
                }
                $output[$key] += $days;
            }
        }
        return $output;
    }
}
