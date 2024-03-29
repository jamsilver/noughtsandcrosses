<?php

class MoveWriteSymbol extends Move
{
    public function __construct(
        private readonly Coordinate $coordinate,
        private readonly Symbol $symbol,
    ) {}

    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }

    public function getSymbol(): Symbol
    {
        return $this->symbol;
    }

    public function applyToBoard(Board $board): void
    {
        $board->writeCell($this->coordinate, $this->symbol);
    }

    public function validateForBoard(Board $board): void
    {
        $board->coordinateIsValid($this->coordinate);

        if ($board->getCell($this->coordinate) !== null) {
            throw new UnexpectedValueException('Square is already filled, pick a blank square.');
        }
    }
}
