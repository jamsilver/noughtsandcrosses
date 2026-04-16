<?php

final class Board1D extends Board
{
    public function newCoordinate(...$points): Coordinate
    {
        return new Coordinate1D(...$points);
    }

    public function newCoordinateFromNotation(string $value): Coordinate
    {
        return Coordinate1D::createFromNotation($value);
    }

    public function forEachCell(callable $callback): self
    {
        for ($x = 0; $x < static::SIZE; $x++) {
            $coordinate = $this->newCoordinate($x);
            $callback($coordinate, $this->getCell($coordinate));
        }
        return $this;
    }

    public function forEachCellAround(Coordinate $epicentre, int $radius, bool $includeEpiCentre, callable $callback): self
    {
        $epicentre = $this->newCoordinate(...$epicentre->getPoints());
        for ($x = max(0, $epicentre->getX() - $radius); $x <= min(static::SIZE - 1, $epicentre->getX() + $radius); $x++) {
            $coordinate = $this->newCoordinate($x);
            if (!$includeEpiCentre && $coordinate == $epicentre) {
                continue;
            }
            $callback($coordinate, $this->getCell($coordinate));
        }
        return $this;
    }

    public function hasWinner(): bool
    {
        return $this->lineIsAWin($this->gatherLineOfCells(0, 0));
    }

    public function __toString(): string
    {
        $output = [];

        for ($x = 0; $x < static::SIZE; $x++) {
            $cells[] = $this->getCell($this->newCoordinate($x))?->value ?? ' ';
        }
        $output[] = ' ' . implode(' | ', $cells);
        $output[] = '';
        $output[] = ' ' . implode('   ', range(1, static::SIZE));
        $output[] = '';

        return implode("\n", $output);
    }
}
