<?php

abstract class Move
{
    public static function createFromNotation(string $value, PlayerSymbol $playerSymbol): Move
    {
        $value = trim(strtoupper($value));

        if (strlen($value) === 0) {
            throw new UnexpectedValueException('Invalid move notation.');
        }

        if (mb_substr($value, 0, 1) === '💥') {
            return new MoveBomb(
                Coordinate::createFromNotation(mb_substr($value, 1)),
                $playerSymbol,
            );
        }

        return new MoveClaimSquare(
            Coordinate::createFromNotation($value),
            $playerSymbol,
        );
    }

    abstract function getPlayerSymbol(): PlayerSymbol;

    abstract function applyToBoard(Board $board): void;

    abstract function validateForBoard(Board $board): void;
}
