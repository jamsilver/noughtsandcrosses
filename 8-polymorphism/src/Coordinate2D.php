<?php

class Coordinate2D extends Coordinate
{
    public function getDimension(): int
    {
        return 2;
    }

    public static function createFromNotation(string $value): static
    {
        $value = trim($value);

        $matches = null;
        if (empty($value) || preg_match('/^([A-Z])([1-9][0-9]*)$/i', $value, $matches) !== 1) {
            throw new UnexpectedValueException('Invalid 2D Coordinate notation.');
        }

        $x = $matches[1];
        $y = $matches[2];

        return new Coordinate2D(
            ord($x) - ord('A'),
            (int) $y - 1
        );
    }

    public function getX(): int
    {
        return $this->points[0];
    }

    public function getY(): int
    {
        return $this->points[1];
    }
}
