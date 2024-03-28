<?php

class Board
{
    private array $symbolsWritten = [];

    private const SIZE = 3;

    public function applyMoveList(MoveList $moveList): self
    {
        foreach ($moveList as $move) {
            $this->applyMove($move);
        }
        return $this;
    }

    public function applyMove(Move $move): self
    {
        $coordinate = $move->getCoordinate();
        $this->symbolsWritten[$coordinate->getX()][$coordinate->getY()] = $move->getSymbol();
        return $this;
    }

    public function validateCoordinateIsAvailable(Coordinate $coordinate): void
    {
        if ($coordinate->getX() < 0 || Board::SIZE <= $coordinate->getX() ||
            $coordinate->getY() < 0 || Board::SIZE <= $coordinate->getY()) {
            throw new UnexpectedValueException( "Invalid input, coordinates outside board range.");
        }

        if (isset($this->symbolsWritten[$coordinate->getX()][$coordinate->getY()])) {
            throw new UnexpectedValueException( "Square is already filled, pick a blank square.");
        }
    }

    public function hasThreeInARow(): bool
    {
        $diagonal1Line = [];
        $diagonal2Line = [];

        for ($i = 0; $i < Board::SIZE; $i++) {
            // Check row i.
            if ($this->lineIsAWin($this->symbolsWritten[$i] ?? [])) {
                return true;
            }
            // Check column i.
            if ($this->lineIsAWin(array_column($this->symbolsWritten, $i))) {
                return true;
            }
            // Use this loop to build up line variables for diagonal 1 and diagonal 2.
            $diagonal1Line[] = $this->symbolsWritten[$i][$i] ?? null;
            $diagonal2Line[] = $this->symbolsWritten[$i][Board::SIZE - 1 - $i] ?? null;
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

    private function lineIsAWin(array $line): bool
    {
        $lineOfStr = array_map(fn(Symbol $s) => $s->value, $line);
        return count($lineOfStr) === Board::SIZE && count(array_unique($lineOfStr)) === 1;
    }

    public function toString(): string
    {
        $output = [];

        $numberPrefixWidth = strlen((string) Board::SIZE);
        $gutter = '   ';

        $rows = [];
        for ($y = 0; $y < Board::SIZE; $y++) {
            $cells = [];
            for ($x = 0; $x < Board::SIZE; $x++) {
                $cells[] = $this->getCell($x, $y);
            }
            $rows[] = str_pad($y + 1, $numberPrefixWidth) . $gutter . ' ' . implode(' | ', $cells);
        }

        $rowSeparator = str_repeat('---', Board::SIZE) . str_repeat('-', Board::SIZE - 1);

        $output[] = implode(
            "\n" . str_repeat(' ', $numberPrefixWidth) . $gutter . $rowSeparator . "\n",
            $rows,
        );

        $columnLetters = array_map(
            fn($i) => chr(ord('A') + $i),
            range(0, Board::SIZE - 1)
        );

        $output[] = '';
        $output[] = str_pad(' ', $numberPrefixWidth) . $gutter . ' ' . implode('   ', $columnLetters);
        $output[] = '';

        return implode("\n", $output);
    }

    private function getCell(int $x, int $y): string
    {
        if (!isset($this->symbolsWritten[$x][$y])) {
            return ' ';
        }

        return $this->symbolsWritten[$x][$y]->value;
    }
}
