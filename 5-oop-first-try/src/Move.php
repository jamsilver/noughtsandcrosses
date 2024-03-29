<?php

class Move
{
    public function __construct(
        private readonly Coordinate $coordinate,
        private readonly PlayerSymbol $symbol,
    ) {}

    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }

    public function getSymbol(): PlayerSymbol
    {
        return $this->symbol;
    }
}
