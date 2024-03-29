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
        for ($y = 0; $y < Board::SIZE; $y++) {
            for ($x = 0; $x < Board::SIZE; $x++) {
                $coordinate = new Coordinate($x, $y);
                if ($board->getCell($coordinate) !== null) {
                    $board->writeCell($coordinate, $board->getCell($coordinate)->flip());
                }
            }
        }
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
