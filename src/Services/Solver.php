<?php

declare(strict_types=1);

namespace JanIvanov\Employees\Services;

use DateTime;
use JanIvanov\Employees\Models\ProjectsStorage;

class Solver
{
    private ProjectsStorage $projects;

    public function __construct(
        private FileCSVReader $file,
    ) {
        $this->projects = new ProjectsStorage();
    }

    /**
     * @return array<int,array{'employee1Id':int, 'employee2Id':int, 'days':int, 'projects':array<int, int>}>
     */
    public function solve(): array
    {
        while ($item = $this->file->getCsvRow()) {
            $this->parseItem($item);
        }

        $daysTogether = $this->projects->getEmployeesThatWorkedTogether();


        if (empty($daysTogether)) {
            return [];
        }
        uasort($daysTogether, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        [$data, $pair] = [reset($daysTogether), key($daysTogether)];

        $pair = explode('-', $pair);

        $answers = [];
        $answers[] = [
            'employee1Id' => intval($pair[0]),
            'employee2Id' => intval($pair[1]),
            'days' => $data['total'],
            'projects' => $data['projects'],
        ];
        return $answers;
    }

    /**
     * @param array<string, string> $item
     */
    private function parseItem(array $item): void
    {
        $startDate = $this->parseDate($item['DateFrom']);
        $endDate = $this->parseDate($item['DateTo']);

        $this->projects->addToProject(
            (int) $item['ProjectID'],
            (int) $item['EmpID'],
            $startDate,
            $endDate
        );
    }

    private function parseDate(string $date): DateTime
    {
        if (empty($date) || $date === 'NULL') {
            return (new DateTime('now'))->setTime(0, 0, 0);
        }
        return new DateTime($date);
    }
}
