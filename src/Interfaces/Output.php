<?php

declare(strict_types=1);

namespace JanIvanov\Employees\Interfaces;

interface Output
{
    public function print(string $message): void;

    /** @phpstan-ignore missingType.iterableValue */
    public function printJson(array $output): void;
}
