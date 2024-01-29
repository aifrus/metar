<?php

namespace Aifrus\METAR\Parts;

class ReportModifiers
{
    public bool $AUTO = false;
    public bool $COR = false;
    public bool $NIL = false;

    public static function create(array &$reportParts): self
    {
        $res = new self;
        foreach ($reportParts as $key => $reportPart) if (property_exists($res, $reportPart)) {
            $res->{$reportPart} = true;
            unset($reportParts[$key]);
        }
        $reportParts = array_values($reportParts);
        return $res;
    }
}
