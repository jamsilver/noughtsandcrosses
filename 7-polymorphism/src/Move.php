<?php

abstract class Move
{
    public static function createFromNotation(string $value, Symbol $symbol): Move
    {
        $value = trim(strtoupper($value));

        if (strlen($value) === 0) {
            throw new UnexpectedValueException('Invalid move notation.');
        }

        return new MoveWriteSymbol(
            Coordinate::createFromNotation($value),
            $symbol,
        );
    }

    abstract function applyToBoard(Board $board): void;

    abstract function validateForBoard(Board $board): void;
}
