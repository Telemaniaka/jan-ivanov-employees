<?php

declare(strict_types=1);

namespace JanIvanov\Employees;

use JanIvanov\Employees\Interfaces\Output;
use JanIvanov\Employees\Services\FileCSVReader;
use JanIvanov\Employees\Services\FileUploadHandler;
use JanIvanov\Employees\Services\Solver;

class App
{
    public function __construct(
        protected Output $output
    ) {
    }

    /**
     * @param string[] $argv
     */
    public function runCli(array $argv): void
    {
        try {
            $inputFileName = $argv[1];
            if (empty($argv[1])) {
                $this->output->print('Missing input filename');
                die();
            }

            $fileReader = new FileCSVReader($inputFileName);

            $solver = new Solver($fileReader);
            $answers = $solver->solve();

            if (empty($answers)) {
                $this->output->print('No solution found');
                return;
            }

            foreach ($answers as $answer) {
                $this->output->print(
                    $answer['employee1Id'] . ", " . $answer['employee2Id'] . ", " . $answer['days']
                );
            }
        } catch (\Exception $e) {
            $this->output->print('Error occured: ' . $e->getMessage());
        }
    }

    public function runWeb(): void
    {
        try {
            // get uploaded file
            $uploadHandler = new FileUploadHandler();

            if ($uploadHandler->getHasError()) {
                $this->output->printJson([
                    'success' => false,
                    'message' => $uploadHandler->getErrorMessage(),
                ]);
                return;
            }

            $inputFileName = $uploadHandler->getFileName();

            // solve
            $fileReader = new FileCSVReader($inputFileName);

            $solver = new Solver($fileReader);
            $answer = $solver->solve();

            //return json with solution
            if (empty($answer)) {
                $this->output->printJson([
                    'success' => false,
                    'message' => 'No solution found',
                ]);
                return;
            }

            $this->output->printJson([
                'success' => true,
                'data' => $answer,
            ]);
        } catch (\Exception $e) {
            print_r($e->getMessage());
            $this->output->print('Error occurred: ' . $e->getMessage());
        }
    }
}
