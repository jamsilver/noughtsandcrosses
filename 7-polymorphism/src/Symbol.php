<?php

enum Symbol: string
{
    case X = 'X';
    case O = 'O';

    public function flip(): Symbol
    {
        return match($this) {
            Symbol::X => Symbol::O,
            default => Symbol::X,
        };
    }
}
