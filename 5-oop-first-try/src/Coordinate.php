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

        if (empty($value) || strlen($value) !== 2) {
            throw new UnexpectedValueException('Invalid Coordinate notation.');
        }

        $x = $value[0];
        $y = $value[1];

        if (!in_array($x, ['A', 'B', 'C'], true) || !is_numeric($y)) {
            throw new UnexpectedValueException('Invalid Coordinate notation.');
        }

        return new Coordinate(
            match ($x) {
                "A" => 0,
                "B" => 1,
                "C" => 2,
            },
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
