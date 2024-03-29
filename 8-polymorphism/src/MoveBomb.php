<?php

class MoveBomb extends Move
{
    static array $playerUsages = [];

    public function __construct(
        protected readonly Coordinate $coordinate,
        protected readonly PlayerSymbol $playerSymbol,
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
        $board->forEachCellAround($this->coordinate, 1, false, function(Coordinate $coordinate, $cell) use ($board) {
            $board->clearCell($coordinate);
        });

        $this->countUsage();
    }

    public function validateForBoard(Board $board): void
    {
        $board->coordinateIsValid($this->coordinate);

        if ($board->getCell($this->coordinate) !== $this->playerSymbol->flip()) {
            throw new UnexpectedValueException("Can only play a bomb on your opponent's square.");
        }

        if ($this->hasBeenUsed()) {
            throw new UnexpectedValueException($this->playerSymbol->value . ' has already played their 💥/💣!');
        }
    }

    protected function countUsage(): void
    {
        MoveBomb::$playerUsages += [ $this->playerSymbol->value => 0];
        MoveBomb::$playerUsages[$this->playerSymbol->value]++;
    }

    protected function hasBeenUsed(): bool
    {
        return (MoveBomb::$playerUsages[$this->playerSymbol->value] ?? 0) > 0;
    }
}
