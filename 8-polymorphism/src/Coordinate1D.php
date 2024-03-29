<?php

class Coordinate1D extends Coordinate
{
    public function getDimension(): int
    {
        return 1;
    }

    public static function createFromNotation(string $value): static
    {
        $value = trim($value);

        if (empty($value) || !is_numeric($value) || (int) $value <= 0) {
            throw new UnexpectedValueException('Invalid 1D Coordinate notation.');
        }

        return new Coordinate1D((int) $value - 1);
    }

    public function getX(): int
    {
        return $this->points[0];
    }
}
