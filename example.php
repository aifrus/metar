<?php

require_once __DIR__ . '/vendor/autoload.php';

use Aifrus\METAR\METAR;

$response = METAR::fetch("KPWM");
print_r($response);

$metar = new METAR("SPECI KPWM 221853Z 00000KT 10SM CLR 22/12 A3010 RMK AO2 SLP191 T02220117");
print_r($metar);

