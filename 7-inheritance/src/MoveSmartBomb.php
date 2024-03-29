<?php

class MoveSmartBomb extends MoveBomb
{
    public function applyToBoard(Board $board): void
    {
        $board->forEachCellAround($this->coordinate, 1, true, function($coordinate, $cell) use ($board) {
            if ($cell === $this->playerSymbol->flip()) {
                $board->clearCell($coordinate);
            }
        });

        $this->countUsage();
    }
}
