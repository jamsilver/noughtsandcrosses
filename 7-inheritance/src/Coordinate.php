<?php

class Coordinate
{
    public function __construct(
        private readonly int $x,
        private readonly int $y
    ) {}

    public static function createFromNotation(string $value): Coordinate
    {
        $value = trim($value);

        $matches = null;
        if (empty($value) || preg_match('/^([A-Z])([1-9][0-9]*)$/i', $value, $matches) !== 1) {
            throw new UnexpectedValueException('Invalid Coordinate notation.');
        }

        $x = $matches[1];
        $y = $matches[2];

        return new Coordinate(
            ord($x) - ord('A'),
            (int) $y - 1
        );
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

}
