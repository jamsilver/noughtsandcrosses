<?php

class Board implements Stringable
{
    private array $symbolsWritten = [];

    public const SIZE = 3;

    public function writeCell(Coordinate $coordinate, PlayerSymbol $symbol): self
    {
        $this->symbolsWritten[$coordinate->getX()][$coordinate->getY()] = $symbol;
        return $this;
    }

    public function clearCell(Coordinate $coordinate): self
    {
        unset($this->symbolsWritten[$coordinate->getX()][$coordinate->getY()]);
        return $this;
    }

    public function getCell(Coordinate $coordinate): ?PlayerSymbol
    {
        return $this->symbolsWritten[$coordinate->getX()][$coordinate->getY()] ?? null;
    }

    public function coordinateIsValid(Coordinate $coordinate): void
    {
        if ($coordinate->getX() < 0 || Board::SIZE <= $coordinate->getX() ||
            $coordinate->getY() < 0 || Board::SIZE <= $coordinate->getY()) {
            throw new UnexpectedValueException( "Invalid input, coordinates outside board range.");
        }
    }

    public function hasWinner(): bool
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
        $lineOfStr = array_map(fn(PlayerSymbol $s) => $s->value, $line);
        return count($lineOfStr) === Board::SIZE && count(array_unique($lineOfStr)) === 1;
    }

    public function __toString(): string
    {
        $output = [];

        $numberPrefixWidth = strlen((string) Board::SIZE);
        $gutter = '   ';

        $rows = [];
        for ($y = 0; $y < Board::SIZE; $y++) {
            $cells = [];
            for ($x = 0; $x < Board::SIZE; $x++) {
                $cells[] = $this->getCell(new Coordinate($x, $y))?->value ?? ' ';
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
}
