<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Aifrus\METAR\METAR;

try {
    print_r(METAR::fetch('KPWM'));
} catch (\Exception $e) {
    print_r($e);
}

