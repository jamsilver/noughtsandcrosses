<?php

enum PlayerSymbol: string
{
    case X = 'X';
    case O = 'O';

    public function flip(): PlayerSymbol
    {
        return match($this) {
            PlayerSymbol::X => PlayerSymbol::O,
            PlayerSymbol::O => PlayerSymbol::X,
        };
    }
}
