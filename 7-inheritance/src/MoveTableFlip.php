<?php

class MoveTableFlip extends Move
{
    static array $playerUsages = [];

    public function __construct(
        private readonly PlayerSymbol $playerSymbol,
    ) {}

    public function getPlayerSymbol(): PlayerSymbol
    {
        return $this->playerSymbol;
    }

    public function applyToBoard(Board $board): void
    {
        $board->forEachCell(function($coordinate, $cell) use ($board) {
            if ($cell !== null) {
                $board->writeCell($coordinate, $cell->flip());
            }
        });
        $this->countUsage();
    }

    public function validateForBoard(Board $board): void
    {
        if ($this->hasBeenUsed()) {
            throw new UnexpectedValueException($this->playerSymbol->value . ' has already played their (╯°□°）╯︵ ┻━┻!');
        }
    }

    private function countUsage(): void
    {
        MoveTableFlip::$playerUsages += [ $this->playerSymbol->value => 0];
        MoveTableFlip::$playerUsages[$this->playerSymbol->value]++;
    }

    private function hasBeenUsed(): bool
    {
        return (MoveTableFlip::$playerUsages[$this->playerSymbol->value] ?? 0) > 0;
    }
}
