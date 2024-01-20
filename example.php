<?php

require_once __DIR__ . '/vendor/autoload.php';

use Aifrus\METAR\METAR;

$response = METAR::fetch("KBGR,KPWM,KSFM,KMHT,KPSM,KBOS");
echo ($response . PHP_EOL);
