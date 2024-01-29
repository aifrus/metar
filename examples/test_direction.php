<?php

namespace Aifrus\METAR;

require_once(__DIR__ . '/../vendor/autoload.php');

for ($i = 0; $i < 360; $i++) {
    $direction = new Parts\Direction($i);
    echo "$i: \t" . $direction->asCardinal4->value . "\t" . $direction->asCardinal8->value . "\t" . $direction->asCardinal16->value . "\n";
}
