<?php

declare(strict_types=1);

namespace JanIvanov\Employees\Services;

use JanIvanov\Employees\Interfaces\Output;

class StdOut implements Output
{
    public function print(string $message): void
    {
        echo $message . "\n";
    }

    /** @phpstan-ignore missingType.iterableValue */
    public function printJson(array $output): void
    {
        echo json_encode($output);
    }
}
