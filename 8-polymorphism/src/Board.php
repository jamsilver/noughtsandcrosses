<?php

abstract class Board implements Stringable
{
    protected array $symbolsWritten = [];

    public const SIZE = 5;

    public function writeCell(Coordinate $coordinate, PlayerSymbol $symbol): self
    {
        $this->symbolsWritten[(string) $coordinate] = [ $coordinate, $symbol ];
        return $this;
    }

    public function clearCell(Coordinate $coordinate): self
    {
        unset($this->symbolsWritten[(string) $coordinate]);
        return $this;
    }

    public function getCell(Coordinate $coordinate): ?PlayerSymbol
    {
        return $this->symbolsWritten[(string) $coordinate][1] ?? null;
    }

    public function hasCell(Coordinate $coordinate): bool
    {
        return isset($this->symbolsWritten[(string) $coordinate]);
    }

    public function coordinateIsValid(Coordinate $coordinate): void
    {
        for ($i = 0; $i < $coordinate->getDimension(); $i++) {
            $point = $coordinate->getNthPoint($i);
            if ($point < 0 || static::SIZE <= $point) {
                throw new UnexpectedValueException( "Invalid input, coordinates outside board range.");
            }
        }
    }

    abstract public function hasWinner(): bool;

    protected function gatherLineOfCells(int $coordinatePointIndex, int $coordinatePointValue): array
    {
        return array_map(
            fn ($v) => $v[1],
            array_filter(
                $this->symbolsWritten,
                fn($v) => $v[0]->getNthPoint($coordinatePointIndex) === $coordinatePointValue,
            ),
        );
    }

    protected function lineIsAWin(array $line): bool
    {
        $lineOfStr = array_map(fn(PlayerSymbol $s) => $s->value, $line);
        return count($lineOfStr) === static::SIZE && count(array_unique($lineOfStr)) === 1;
    }

    abstract public function newCoordinate(...$points): Coordinate;

    abstract public function newCoordinateFromNotation(string $value): Coordinate;

    abstract public function forEachCell(callable $callback): self;

    abstract public function forEachCellAround(Coordinate $epicentre, int $radius, bool $includeEpiCentre, callable $callback): self;

    abstract public function __toString(): string;
}
