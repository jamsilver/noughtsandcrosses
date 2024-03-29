<?php

class MoveBomb extends Move
{
    static array $playerUsages = [];

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
        // Flip targeted cell to mine.
        $board->writeCell($this->coordinate, $this->playerSymbol);

        // Delete surrounding cells.
        for ($x = max(0, $this->coordinate->getX() - 1); $x <= min(Board::SIZE - 1, $this->coordinate->getX() + 1); $x++) {
            for ($y = max(0, $this->coordinate->getY() - 1); $y <= min(Board::SIZE - 1, $this->coordinate->getY() + 1); $y++) {
                if ($x === $this->coordinate->getX() && $y === $this->coordinate->getY()) {
                    continue;
                }
                $board->clearCell(new Coordinate($x, $y));
            }
        }

        $this->countUsage();
    }

    public function validateForBoard(Board $board): void
    {
        $board->coordinateIsValid($this->coordinate);

        if ($board->getCell($this->coordinate) !== $this->playerSymbol->flip()) {
            throw new UnexpectedValueException("Can only play a bomb on your opponent's square.");
        }

        if ($this->hasBeenUsed()) {
            throw new UnexpectedValueException($this->playerSymbol->value . ' has already played their 💥!');
        }
    }

    private function countUsage(): void
    {
        MoveBomb::$playerUsages += [ $this->playerSymbol->value => 0];
        MoveBomb::$playerUsages[$this->playerSymbol->value]++;
    }

    private function hasBeenUsed(): bool
    {
        return (MoveBomb::$playerUsages[$this->playerSymbol->value] ?? 0) > 0;
    }
}
