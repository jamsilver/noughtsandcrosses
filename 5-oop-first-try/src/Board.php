<?php

class Board
{
    private array $board;

    public function __construct()
    {
        $this->board = [
            [' ', ' ', ' '],
            [' ', ' ', ' '],
            [' ', ' ', ' '],
        ];
    }

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
        $this->board[$coordinate->getX()][$coordinate->getY()] = $move->getSymbol();
        return $this;
    }

    public function validateCoordinateIsAvailable(Coordinate $coordinate): void
    {
        if (!isset($this->board[$coordinate->getX()][$coordinate->getY()])) {
            throw new UnexpectedValueException("Invalid input. Use coordinates.");
        }

        if ($this->board[$coordinate->getX()][$coordinate->getY()] !== ' ') {
            throw new UnexpectedValueException( "Square is already filled, pick a blank square.");
        }
    }

    public function hasThreeInARow(): bool
    {
        return ($this->getCell(0, 0) !== ' ' && $this->getCell(0, 0) === $this->getCell(0, 1) && $this->getCell(0, 0) === $this->getCell(0, 2)) ||
            ($this->getCell(1, 0) !== ' ' && $this->getCell(1, 0) === $this->getCell(1, 1) && $this->getCell(1, 0) === $this->getCell(1, 2)) ||
            ($this->getCell(2, 0) !== ' ' && $this->getCell(2, 0) === $this->getCell(2, 1) && $this->getCell(2, 0) === $this->getCell(2, 2)) ||
            ($this->getCell(0, 0) !== ' ' && $this->getCell(0, 0) === $this->getCell(1, 0) && $this->getCell(0, 0) === $this->getCell(2, 0)) ||
            ($this->getCell(0, 1) !== ' ' && $this->getCell(0, 1) === $this->getCell(1, 1) && $this->getCell(0, 1) === $this->getCell(2, 1)) ||
            ($this->getCell(0, 2) !== ' ' && $this->getCell(0, 2) === $this->getCell(1, 2) && $this->getCell(0, 2) === $this->getCell(2, 2)) ||
            ($this->getCell(0, 0) !== ' ' && $this->getCell(0, 0) === $this->getCell(1, 1) && $this->getCell(0, 0) === $this->getCell(2, 2)) ||
            ($this->getCell(2, 0) !== ' ' && $this->getCell(2, 0) === $this->getCell(1, 1) && $this->getCell(2, 0) === $this->getCell(0, 2));
    }

    public function toString(): string
    {
        $output = [];
        $output[] = "1    {$this->getCell(0, 0)} | {$this->getCell(1, 0)} | {$this->getCell(2, 0)}";
        $output[] = "    -----------";
        $output[] = "2    {$this->getCell(0, 1)} | {$this->getCell(1, 1)} | {$this->getCell(2, 1)}";
        $output[] = "    -----------";
        $output[] = "3    {$this->getCell(0, 2)} | {$this->getCell(1, 2)} | {$this->getCell(2, 2)}";
        $output[] = '';
        $output[] = "     A | B | C";
        $output[] = '';

        return implode("\n", $output);
    }

    private function getCell(int $x, int $y): string
    {
        $cell = $this->board[$x][$y];
        return $cell instanceof PlayerSymbol ? $cell->value : $cell;
    }
}
