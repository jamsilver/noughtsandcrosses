<?php

abstract class Move
{
    public static function createFromNotation(string $value, Symbol $playerSymbol): Move
    {
        $value = trim(strtoupper($value));

        if (strlen($value) === 0) {
            throw new UnexpectedValueException('Invalid move notation.');
        }

        if ($value[0] === '💥') {
            return new MoveBomb(
                Coordinate::createFromNotation(substr($value, 1)),
                $playerSymbol,
            );
        }

        return new MoveClaimSquare(
            Coordinate::createFromNotation($value),
            $playerSymbol,
        );
    }

    abstract function applyToBoard(Board $board): void;

    abstract function validateForBoard(Board $board): void;
}
