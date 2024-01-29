<?php

namespace Aifrus\METAR\Parts;

use Aifrus\METAR\Exceptions\METARException;

class Timestamp
{
    public ?string $timestampString = null;
    public ?int $day = null;
    public ?int $hour = null;
    public ?int $minute = null;
    public ?int $age = null;

    public static function create(string $timestampString): self
    {
        return new self($timestampString);
    }

    public function __construct(string $timestampString)
    {
        $this->timestampString = $timestampString;
        $this->day = (int) substr($timestampString, 0, 2);
        $this->hour = (int) substr($timestampString, 2, 2);
        $this->minute = (int) substr($timestampString, 4, 2);
        $this->validate();
        $this->age = $this->getAge();
    }

    private function validate(): void
    {
        if ($this->day < 1 || $this->day > 31) throw new METARException('Invalid day');
        if ($this->hour < 0 || $this->hour > 23) throw new METARException('Invalid hour');
        if ($this->minute < 0 || $this->minute > 59) throw new METARException('Invalid minute');
    }

    public function __toString(): string
    {
        return $this->timestampString;
    }

    public function getAge(): int
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $timestamp = new \DateTime($now->format('Y-m') . '-' . $this->day . ' ' . $this->hour . ':' . $this->minute . ':00', new \DateTimeZone('UTC'));
        if ($timestamp > $now) $timestamp->modify('-1 month');
        return round(($now->getTimestamp() - $timestamp->getTimestamp()) / 60, 0);
    }
}
