<?php

class MoveClaimSquare extends Move
{
    public function __construct(
        private readonly Coordinate $coordinate,
        private readonly PlayerSymbol $playerSymbol,
    ) {}

    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }

    public function getPlayerSymbol(): PlayerSymbol
    {
        return $this->playerSymbol;
    }

    public function applyToBoard(Board $board): void
    {
        $board->writeCell($this->coordinate, $this->playerSymbol);
    }

    public function validateForBoard(Board $board): void
    {
        $board->coordinateIsValid($this->coordinate);

        if ($board->getCell($this->coordinate) !== null) {
            throw new UnexpectedValueException('Square is already filled, pick a blank square.');
        }
    }
}
