<?php

abstract class Coordinate implements Stringable
{
    protected array $points = [];

    public function __construct(...$points)
    {
        $dimension = $this->getDimension();

        foreach ($points as $point) {
            if (!is_int($point)) {
                throw new UnexpectedValueException('Points must be integers.');
            }
        }

        $this->points = array_slice(array_pad($points, $dimension, 0), 0, $dimension);
    }

    public static function createFromCoordinate(Coordinate $other): static
    {
        return new static(...$other->getPoints());
    }

    public function getNthPoint(int $n): int
    {
        if ($n < 0 || $this->getDimension() <= $n) {
            throw new UnexpectedValueException(sprintf(
                'Cannot get %s point of a Coordinate of dimension %s',
                $n,
                $this->getDimension(),
            ));
        }
        return $this->points[$n];
    }

    public function getPoints(): array
    {
        return $this->points;
    }

    abstract public function getDimension(): int;

    abstract public static function createFromNotation(string $value): static;

    public function __toString(): string
    {
        return '[' . implode(',', $this->points) . ']';
    }
}
