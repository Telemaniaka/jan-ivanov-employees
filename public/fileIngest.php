<?php
require __DIR__ . '/../vendor/autoload.php';

use JanIvanov\Employees\App;
use JanIvanov\Employees\Services\StdOut;

$output = new StdOut();
$app    = new App($output);
$app->runWeb();
