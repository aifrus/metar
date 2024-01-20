<?php

require_once __DIR__ . '/vendor/autoload.php';

use Aifrus\METAR\METAR;

$response = METAR::fetch("KPWM");
print_r($response);
