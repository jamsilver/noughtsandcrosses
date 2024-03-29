<?php

final class Board2D extends Board
{
    public function newCoordinate(...$points): Coordinate
    {
        return new Coordinate2D(...$points);
    }

    public function newCoordinateFromNotation(string $value): Coordinate
    {
        return Coordinate2D::createFromNotation($value);
    }

    public function forEachCell(callable $callback): self
    {
        for ($y = 0; $y < static::SIZE; $y++) {
            for ($x = 0; $x < static::SIZE; $x++) {
                $coordinate = $this->newCoordinate($x, $y);
                $callback($coordinate, $this->getCell($coordinate));
            }
        }
        return $this;
    }

    public function forEachCellAround(Coordinate $epicentre, int $radius, bool $includeEpiCentre, callable $callback): self
    {
        $epicentre = $this->newCoordinate($epicentre);
        for ($x = max(0, $epicentre->getX() - $radius); $x <= min(static::SIZE - 1, $epicentre->getX() + $radius); $x++) {
            for ($y = max(0, $epicentre->getY() - $radius); $y <= min(static::SIZE - 1, $epicentre->getY() + $radius); $y++) {
                $coordinate = $this->newCoordinate($x, $y);
                if (!$includeEpiCentre && $coordinate == $epicentre) {
                    continue;
                }
                $callback($coordinate, $this->getCell($coordinate));
            }
        }
        return $this;
    }

    public function hasWinner(): bool
    {
        $diagonal1Line = [];
        $diagonal2Line = [];

        for ($i = 0; $i < static::SIZE; $i++) {
            // Check row i.
            if ($this->lineIsAWin($this->gatherLineOfCells(0, $i))) {
                return true;
            }
            // Check column i.
            if ($this->lineIsAWin($this->gatherLineOfCells(1, $i))) {
                return true;
            }
            // Use this loop to build up line variables for diagonal 1 and diagonal 2.
            $diagonal1Line[] = $this->getCell($this->newCoordinate($i, $i)) ?? null;
            $diagonal2Line[] = $this->getCell($this->newCoordinate($i, static::SIZE - 1 - $i)) ?? null;
        }

        // Check the two diagonals.
        if ($this->lineIsAWin(array_filter($diagonal1Line))) {
            return true;
        }
        if ($this->lineIsAWin(array_filter($diagonal2Line))) {
            return true;
        }

        return false;
    }

    public function __toString(): string
    {
        $output = [];

        $numberPrefixWidth = strlen((string) static::SIZE);
        $gutter = '   ';

        $rows = [];
        for ($y = 0; $y < static::SIZE; $y++) {
            $cells = [];
            for ($x = 0; $x < static::SIZE; $x++) {
                $cells[] = $this->getCell($this->newCoordinate($x, $y))?->value ?? ' ';
            }
            $rows[] = str_pad($y + 1, $numberPrefixWidth) . $gutter . ' ' . implode(' | ', $cells);
        }

        $rowSeparator = str_repeat('---', static::SIZE) . str_repeat('-', static::SIZE - 1);

        $output[] = implode(
            "\n" . str_repeat(' ', $numberPrefixWidth) . $gutter . $rowSeparator . "\n",
            $rows,
        );

        $columnLetters = array_map(
            fn($i) => chr(ord('A') + $i),
            range(0, static::SIZE - 1)
        );

        $output[] = '';
        $output[] = str_pad(' ', $numberPrefixWidth) . $gutter . ' ' . implode('   ', $columnLetters);
        $output[] = '';

        return implode("\n", $output);
    }
}
