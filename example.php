<?php

require_once __DIR__ . '/vendor/autoload.php';

use Aifrus\METAR\METAR;

$metar = new METAR('KJFK 111651Z 23012KT 10SM FEW060 SCT250 31/21 A2992 RMK AO2 SLP130 T03060206');
print_r($response);
