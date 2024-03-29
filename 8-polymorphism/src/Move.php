<?php

abstract class Move
{
    public static function createFromNotation(string $value, PlayerSymbol $playerSymbol, Board $board): Move
    {
        $value = trim($value);

        if (strlen($value) === 0) {
            throw new UnexpectedValueException('Invalid move notation.');
        }

        if (mb_substr($value, 0, 1) === '💥') {
            return new MoveBomb(
                $board->newCoordinateFromNotation(mb_substr($value, 1)),
                $playerSymbol,
            );
        }

        if (mb_substr($value, 0, 1) === '💣') {
            return new MoveSmartBomb(
                $board->newCoordinateFromNotation(mb_substr($value, 1)),
                $playerSymbol,
            );
        }

        if ($value === '(╯°□°）╯︵ ┻━┻') {
            return new MoveTableFlip($playerSymbol);
        }

        return new MoveClaimSquare(
            $board->newCoordinateFromNotation($value),
            $playerSymbol,
        );
    }

    abstract function getPlayerSymbol(): PlayerSymbol;

    abstract function applyToBoard(Board $board): void;

    abstract function validateForBoard(Board $board): void;
}
