<?php

class MoveSmartBomb extends MoveBomb
{
    public function applyToBoard(Board $board): void
    {
        // Delete opponent symbols in surrounding area..
        for ($x = max(0, $this->coordinate->getX() - 1); $x <= min(Board::SIZE - 1, $this->coordinate->getX() + 1); $x++) {
            for ($y = max(0, $this->coordinate->getY() - 1); $y <= min(Board::SIZE - 1, $this->coordinate->getY() + 1); $y++) {
                $coordinate = new Coordinate($x, $y);
                if ($board->getCell($coordinate) === $this->playerSymbol->flip()) {
                    $board->clearCell($coordinate);
                }
            }
        }

        $this->countUsage();
    }
}
