<?php

class Coordinate3D extends Coordinate
{
    public function getDimension(): int
    {
        return 3;
    }

    public static function createFromNotation(string $value): static
    {
        $value = trim($value);

        $matches = null;
        if (empty($value) || preg_match('/^([A-Z])([1-9][0-9]*)([a-z])$/', $value, $matches) !== 1) {
            throw new UnexpectedValueException('Invalid 3D Coordinate notation.');
        }

        $x = $matches[1];
        $y = $matches[2];
        $z = $matches[3];

        return new Coordinate3D(
            ord($x) - ord('A'),
            (int) $y - 1,
            ord($z) - ord('a'),
        );
    }

    public function getX(): int
    {
        return $this->points[1];
    }

    public function getY(): int
    {
        return $this->points[1];
    }

    public function getZ(): int
    {
        return $this->points[2];
    }
}
