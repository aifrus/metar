<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Aifrus\METAR\METAR;

$reportString = 'KPWM 251751Z 29003KT 10SM FEW006 FEW120 SCT200 BKN250 03/02 A3012 RMK AO2 SLP202 T00280017 10028 20000 50002';

try {
    print_r(METAR::create($reportString));
} catch (\Exception $e) {
    print_r($e);
}
